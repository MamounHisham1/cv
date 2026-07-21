<?php

namespace App\Livewire;

use App\Ai\Agents\CvBuilderAgent;
use App\Livewire\Concerns\RequiresCredits;
use App\Models\Cv;
use App\Models\CvVersion;
use App\Services\ApplyCvChanges;
use App\Services\CreditManager;
use App\Services\PendingClarifications;
use App\Services\ProposedCvChanges;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\ConversationStore;
use Livewire\Attributes\On;
use Livewire\Component;

class CvAiChat extends Component
{
    use RequiresCredits;

    public ?Cv $cv = null;

    public array $messages = [];

    public string $userMessage = '';

    public bool $isLoading = false;

    public ?string $conversationId = null;

    /**
     * Active clarifying questions waiting for the user's answers.
     * Each entry: {id, question, why, example}. Null when none pending.
     */
    public ?array $pendingClarifications = null;

    /**
     * User's draft answers, keyed by question id. Bound to the input fields
     * via wire:model so the user can fill them in before submitting.
     *
     * @var array<string, string>
     */
    public array $clarificationAnswers = [];

    /**
     * Zero-based index of the clarifying question currently shown in the
     * wizard. The user answers one at a time and steps forward/back.
     */
    public int $currentClarificationIndex = 0;

    /**
     * Proposed CV edits staged by the AI tools, awaiting user review.
     * Each entry: {id, action, section, record_id, before, after, label, summary}.
     * Null when nothing is pending. Nothing here is applied until approveChanges().
     */
    public ?array $proposedChanges = null;

    /**
     * Ids the user has deselected in the review card (excluded from apply).
     *
     * @var array<int, string>
     */
    public array $rejectedChangeIds = [];

    /**
     * Id of the CvVersion snapshot taken before the last AI turn that
     * proposed changes — powers the "Undo AI changes" safety net.
     */
    public ?int $lastTurnVersionId = null;

    public function mount(?Cv $cv = null): void
    {
        // Enforce ownership before anything else — the AI tools operate on
        // $this->cv directly, so the mount is the single trust boundary.
        if ($cv && $cv->exists) {
            $this->authorize('view', $cv);
        }

        $this->cv = $cv;

        // Restore the last conversation for this user from the AI SDK store
        if (Auth::check()) {
            $store = app(ConversationStore::class);
            $lastId = $store->latestConversationId(Auth::id());

            if ($lastId) {
                $rows = DB::table('agent_conversation_messages')
                    ->where('conversation_id', $lastId)
                    ->orderBy('created_at')
                    ->get();

                if ($rows->isNotEmpty()) {
                    $this->conversationId = $lastId;

                    foreach ($rows as $row) {
                        $this->messages[] = [
                            'role' => $row->role,
                            'content' => $row->content,
                            'timestamp' => $row->created_at ?? now()->toISOString(),
                        ];
                    }

                    return;
                }
            }
        }

        $this->messages = [
            [
                'role' => 'assistant',
                'content' => "Hello! I'm your ATS CV Builder assistant. I can help you:\n\n".
                    "• Craft compelling project descriptions\n".
                    "• Optimize your CV for ATS systems\n".
                    "• Suggest relevant keywords for your industry\n".
                    "• Analyze job descriptions for keyword matching\n".
                    "• Generate professional summaries\n".
                    "• Recommend the best CV template\n\n".
                    'What would you like help with today?',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }

    #[On('cv-saved')]
    public function onCvSaved($cvId): void
    {
        $this->cv = Cv::find($cvId);
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->userMessage))) {
            return;
        }

        $message = trim($this->userMessage);
        $this->userMessage = '';

        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
            'timestamp' => now()->toISOString(),
        ];

        $this->isLoading = true;

        $this->dispatch('message-added');
    }

    public function fetchAiResponse(string $message): void
    {
        $this->getAiResponse($message);

        $this->isLoading = false;

        $this->dispatch('message-added');
    }

    private function getAiResponse(string $message): void
    {
        try {
            $creditManager = app(CreditManager::class);

            $freeMessagesRemaining = $this->freeBuilderMessagesRemaining($this->conversationId);
            $isFreeMessage = $freeMessagesRemaining > 0;

            if (! $isFreeMessage && ! $this->hasAnyCredits()) {
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => "You're out of credits. Invite friends to earn more, or upgrade your plan to continue building your CV.",
                    'timestamp' => now()->toISOString(),
                    'is_error' => true,
                ];

                $this->dispatchInsufficientCredits();

                return;
            }

            // Fresh bridges for this turn — the AI tools write to these
            // singletons during the agent run; we read them back after.
            $pending = app(PendingClarifications::class);
            $pending->clear();
            $proposed = app(ProposedCvChanges::class);
            $proposed->clear();

            $agent = new CvBuilderAgent($this->cv);

            if ($this->conversationId) {
                $agent = $agent->continue($this->conversationId, as: Auth::user());
            } else {
                $agent = $agent->forUser(Auth::user());
            }

            $response = $agent->prompt($message);

            $this->conversationId = $response->conversationId;

            $content = (string) $response;

            $content = preg_replace('/\R{3,}/', "\n\n", trim($content));

            // If the agent asked clarifying questions via the tool, capture
            // them into Livewire state so the interactive UI renders. The
            // user-facing message becomes a short prompt to answer below;
            // the model's "[INTERNAL: STOP...]" string is discarded.
            if ($pending->has()) {
                $this->pendingClarifications = $pending->get();
                $this->currentClarificationIndex = 0;
                $content = $this->buildClarificationsIntro(count($this->pendingClarifications));

                // HARD GUARD: asking questions AND staging changes in the same
                // turn is contradictory (the model was instructed to STOP and
                // wait for answers before writing). This model ignores that
                // instruction, so we enforce it here: any changes staged in a
                // turn that also asked questions are discarded. The agent will
                // re-propose them — correctly — after the user answers.
                $proposed->clear();
            } else {
                $this->pendingClarifications = null;
                $this->currentClarificationIndex = 0;
            }

            // If the AI tools staged any CV edits, capture them for the
            // review card. Nothing is applied yet — the user must approve.
            // We also snapshot the CV *before* approval so a one-click Undo
            // always exists even if a bad edit slips past review.
            //
            // (Cleared above when clarifications were asked — questions take
            // precedence over edits in the same turn.)
            if ($proposed->has()) {
                $this->proposedChanges = $proposed->all();
                $this->rejectedChangeIds = [];
                if ($this->cv && $this->cv->exists) {
                    $this->lastTurnVersionId = CvVersion::capture(
                        $this->cv,
                        'before AI turn'
                    )->id;
                }
            } else {
                $this->proposedChanges = null;
            }

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $content,
                'timestamp' => now()->toISOString(),
            ];

            if (! $isFreeMessage && $response->usage) {
                $credits = $this->calculateCreditsFromUsage($response->usage, 'ai_builder_message');
                $creditManager->deduct(Auth::user(), $credits, 'ai_builder_message', null, [
                    'prompt_tokens' => $response->usage->promptTokens,
                    'completion_tokens' => $response->usage->completionTokens,
                    'conversation_id' => $this->conversationId,
                ]);
                $this->dispatch('credits-updated');
            }

            // NOTE: we deliberately do NOT dispatch cv-updated here. A chat
            // turn only stages clarifications/proposals — nothing is written
            // to the CV until the user approves. Dispatching cv-updated would
            // re-render the parent (cv-builder) and wipe this component's
            // pendingClarifications/proposedChanges state. cv-updated is sent
            // from approveChanges()/undoLastTurn(), where the CV really mutates.
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => "I apologize, but I encountered an error processing your request. Please try again or rephrase your question.\n\nError: {$e->getMessage()}",
                'timestamp' => now()->toISOString(),
                'is_error' => true,
            ];
        }
    }

    /**
     * Handle the user's answers to the pending clarifying questions.
     * Packs the answers into a single user message and feeds it back to
     * the agent so it can proceed using only the supplied facts.
     *
     * @param  array<string, string>  $answers
     */
    public function submitClarifications(array $answers): void
    {
        if (empty($this->pendingClarifications)) {
            return;
        }

        $lines = [];
        foreach ($this->pendingClarifications as $q) {
            $id = $q['id'];
            $answer = trim((string) ($answers[$id] ?? ''));
            if ($answer === '') {
                continue;
            }
            $lines[] = "**Q:** {$q['question']}\n**A:** {$answer}";
        }

        $this->pendingClarifications = null;
        $this->clarificationAnswers = [];
        $this->currentClarificationIndex = 0;

        $packed = empty($lines)
            ? '(I skipped the clarifying questions — proceed with only the facts already in my CV.)'
            : "Here are my answers to your questions:\n\n".implode("\n\n", $lines);

        $this->messages[] = [
            'role' => 'user',
            'content' => $packed,
            'timestamp' => now()->toISOString(),
        ];

        // Drive the next agent turn server-side, mirroring fetchAiResponse.
        $this->isLoading = true;
        $this->getAiResponse($packed);
        $this->isLoading = false;

        $this->dispatch('message-added');
    }

    /**
     * Convenience entrypoint so the Blade form can submit the bound
     * clarificationAnswers array directly via wire:submit.
     */
    public function submitClarificationsForm(): void
    {
        $this->submitClarifications($this->clarificationAnswers);
    }

    public function skipClarifications(): void
    {
        $this->submitClarifications([]);
    }

    /**
     * Advance the wizard to the next clarifying question, or submit if this
     * was the last one.
     */
    public function nextClarification(): void
    {
        if (empty($this->pendingClarifications)) {
            return;
        }

        if ($this->currentClarificationIndex < count($this->pendingClarifications) - 1) {
            $this->currentClarificationIndex++;
        } else {
            $this->submitClarificationsForm();
        }
    }

    /**
     * Step the wizard back to the previous clarifying question.
     */
    public function previousClarification(): void
    {
        if ($this->currentClarificationIndex > 0) {
            $this->currentClarificationIndex--;
        }
    }

    private function buildClarificationsIntro(int $count): string
    {
        $noun = $count === 1 ? 'quick question' : "{$count} quick questions";

        return "Before I change anything, I need {$noun} so I'm not guessing. Please answer below 👇";
    }

    /**
     * Apply the proposed CV edits the user kept (everything not in
     * rejectedChangeIds). Runs in a single transaction via ApplyCvChanges.
     */
    public function approveChanges(): void
    {
        if (empty($this->proposedChanges) || ! $this->cv || ! $this->cv->exists) {
            return;
        }

        $approved = array_values(array_filter(
            $this->proposedChanges,
            fn ($op) => ! in_array($op['id'], $this->rejectedChangeIds, true),
        ));

        $result = app(ApplyCvChanges::class)->apply($this->cv, $approved);

        // Clear the review state BEFORE dispatching cv-updated, so the parent
        // re-render doesn't see stale proposedChanges (which would cause a
        // visible flash of the card disappearing mid-morph).
        $this->proposedChanges = null;
        $this->rejectedChangeIds = [];

        // Use the app's global toast system (listens for the `notify` window
        // event in the layout) — not a chat message, which would persist.
        $summary = $result['applied'].' change'.($result['applied'] === 1 ? '' : 's').' applied';
        if ($result['skipped'] > 0) {
            $summary .= ', '.$result['skipped'].' skipped';
        }
        $this->dispatch('notify', message: $summary, type: 'success');

        $this->cv->refresh();
        // Refresh ONLY the preview — a global cv-updated would re-render the
        // parent cv-builder and morph this chat, causing a visible flicker.
        $this->dispatch('cv-applied-from-chat', cvId: $this->cv->id);
    }

    /**
     * Discard every staged proposal without applying anything. The snapshot
     * is retained so the Undo button still works if needed.
     */
    public function rejectAllChanges(): void
    {
        if (empty($this->proposedChanges)) {
            return;
        }

        $count = count($this->proposedChanges);

        $this->proposedChanges = null;
        $this->rejectedChangeIds = [];

        $this->dispatch('notify', message: "Rejected {$count} proposed change".($count === 1 ? '' : 's').' — nothing was modified.', type: 'info');
    }

    /**
     * Toggle whether a single proposed change is included in the next apply.
     */
    public function toggleChange(string $id): void
    {
        if (in_array($id, $this->rejectedChangeIds, true)) {
            $this->rejectedChangeIds = array_values(array_diff($this->rejectedChangeIds, [$id]));
        } else {
            $this->rejectedChangeIds[] = $id;
        }
    }

    /**
     * Restore the CV to the snapshot taken before the last AI turn.
     */
    public function undoLastTurn(): void
    {
        if (! $this->lastTurnVersionId) {
            return;
        }

        $version = CvVersion::find($this->lastTurnVersionId);
        if (! $version) {
            $this->lastTurnVersionId = null;

            return;
        }

        $version->revert();

        $this->lastTurnVersionId = null;
        $this->proposedChanges = null;

        $this->dispatch('notify', message: 'Reverted the CV to how it was before the last AI changes.', type: 'info');

        if ($this->cv) {
            $this->cv->refresh();
            // Refresh ONLY the preview (see approveChanges for rationale).
            $this->dispatch('cv-applied-from-chat', cvId: $this->cv->id);
        }
    }

    public function quickPrompt(string $type): void
    {
        $info = $this->cv?->personal_info ?? [];
        $title = $info['title'] ?? null;
        $targetRole = trim((string) $title) ?: 'my target role';

        $prompts = [
            'improve_summary' => 'Review my professional summary and tell me what specific facts or metrics are missing to make it stronger. Ask me for those details before rewriting it — do not invent anything.',
            'keywords' => "What keywords should I include for {$targetRole}?",
            'ats_check' => 'Review my CV and suggest ATS optimization improvements.',
            'job_match' => 'Analyze this job description and tell me what keywords I should add to my CV.',
            'template' => 'What CV template would work best for a senior position applying to startups?',
            'project' => 'I want to improve a project description. Ask me what metrics, scale, and outcomes I can confirm so you can rewrite it using only real facts.',
        ];

        if (isset($prompts[$type])) {
            $this->userMessage = $prompts[$type];
            $this->sendMessage();
        }
    }

    public function clearChat(): void
    {
        $this->messages = [
            [
                'role' => 'assistant',
                'content' => 'Chat cleared. How can I help you today?',
                'timestamp' => now()->toISOString(),
            ],
        ];
        $this->conversationId = null;
        $this->pendingClarifications = null;
        $this->clarificationAnswers = [];
        $this->currentClarificationIndex = 0;
        $this->proposedChanges = null;
        $this->rejectedChangeIds = [];
        $this->lastTurnVersionId = null;
    }

    public function placeholder()
    {
        return view('livewire.partials.cv-ai-chat-skeleton');
    }

    public function render()
    {
        return view('livewire.cv-ai-chat');
    }
}
