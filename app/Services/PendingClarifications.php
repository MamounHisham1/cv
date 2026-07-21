<?php

namespace App\Services;

/**
 * Bridges clarifying questions from the AskClarifyingQuestions tool (which
 * runs deep inside the agent loop) back to the Livewire component that
 * invoked the agent.
 *
 * Registered as a singleton: one instance per request/Livewire round-trip.
 * The tool writes the questions here during the agent run; the Livewire
 * component reads them immediately after $agent->prompt() returns and
 * renders the interactive question UI.
 */
class PendingClarifications
{
    /** @var array<int, array{id: string, question: string, why: string, example: string}>|null */
    protected ?array $questions = null;

    /**
     * Store a batch of clarifying questions and assign each a stable id.
     *
     * @param  array<int, array{question: string, why?: string, example?: string}>  $questions
     */
    public function set(array $questions): void
    {
        $normalized = [];

        foreach ($questions as $i => $q) {
            $question = trim((string) ($q['question'] ?? ''));

            if ($question === '') {
                continue;
            }

            $normalized[] = [
                'id' => 'q_'.($i + 1),
                'question' => $question,
                'why' => trim((string) ($q['why'] ?? '')),
                'example' => trim((string) ($q['example'] ?? '')),
            ];
        }

        $this->questions = $normalized === [] ? null : $normalized;
    }

    /**
     * @return array<int, array{id: string, question: string, why: string, example: string}>|null
     */
    public function get(): ?array
    {
        return $this->questions;
    }

    public function has(): bool
    {
        return ! empty($this->questions);
    }

    public function clear(): void
    {
        $this->questions = null;
    }
}
