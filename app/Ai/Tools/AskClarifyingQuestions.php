<?php

namespace App\Ai\Tools;

use App\Services\PendingClarifications;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * Surfaces the agent's clarifying questions to the user and halts the turn.
 *
 * This enforces the truthfulness contract in CvBuilderAgent: when the facts
 * needed to improve content (metrics, dates, technologies) are missing, the
 * agent must ask for them rather than invent them. The tool stores the
 * structured questions in the PendingClarifications singleton so the
 * Livewire component can render the interactive answer UI; it then returns
 * a hard "stop and wait" instruction to the model so it cannot proceed to
 * write fabricated content in the same turn.
 */
class AskClarifyingQuestions implements Tool
{
    public function __construct(
        private readonly PendingClarifications $pending,
    ) {}

    /**
     * Explicit snake_case name so it matches the literal string referenced
     * in CvBuilderAgent's instructions (the SDK otherwise exposes the
     * PascalCase class basename, which the model may fail to match).
     */
    public function name(): string
    {
        return 'ask_clarifying_questions';
    }

    public function description(): Stringable|string
    {
        return 'Ask the user clarifying questions to gather facts you need before editing their CV. Use this whenever you are about to improve, expand, or rewrite CV content but are missing concrete facts (metrics, dates, technologies, team sizes, outcomes, etc.). NEVER guess or invent these details, and NEVER write clarifying questions as plain text in your reply — ALWAYS call this tool instead. The questions will be shown to the user as an interactive form. After calling this tool, STOP and wait for the user to answer before making any changes.';
    }

    public function handle(Request $request): Stringable|string
    {
        $questions = $request['questions'] ?? [];

        if (! is_array($questions) || empty($questions)) {
            return 'No questions provided. If you have everything you need, proceed using only confirmed facts; if you are missing facts, provide at least one clarifying question.';
        }

        // Normalize and hand off to the bridge so the Livewire component
        // can render the interactive question UI.
        $this->pending->set($questions);

        if (! $this->pending->has()) {
            return 'No valid questions provided. Each question needs at least a "question" field.';
        }

        $count = count($this->pending->get());

        // Returned to the MODEL — instruct it to end its turn here. The
        // UI rendering is handled separately by the Livewire component.
        return "Clarifying questions ({$count}) have been shown to the user as an interactive form. STOP here — do not call any update_*, add_*, or write tool in this turn. Wait for the user to answer in the next message, then proceed using ONLY the facts they provide.";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'questions' => $schema->array()
                ->description('The clarifying questions to ask the user before editing the CV')
                ->items($schema->object([
                    'question' => $schema->string()->description('The specific question to ask (e.g., "How many concurrent users did the app handle?")'),
                    'why' => $schema->string()->description('Brief reason this fact is needed (e.g., "To quantify the scale of the project")'),
                    'example' => $schema->string()->description('A concrete example answer to help the user (e.g., "e.g., around 1,000 peak users")'),
                ])),
        ];
    }
}
