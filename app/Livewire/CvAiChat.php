<?php

namespace App\Livewire;

use App\Ai\Agents\CvBuilderAgent;
use App\Models\Cv;
use App\Services\CreditManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\ConversationStore;
use Livewire\Attributes\On;
use Livewire\Component;

class CvAiChat extends Component
{
    public ?Cv $cv = null;

    public array $messages = [];

    public string $userMessage = '';

    public bool $isLoading = false;

    public ?string $conversationId = null;

    public function mount(?Cv $cv = null): void
    {
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
    public function onCvSaved(int $cvId): void
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
            $user = Auth::user();

            $freeMessagesRemaining = $creditManager->getFreeBuilderMessagesRemaining($user, $this->conversationId);
            $isFreeMessage = $freeMessagesRemaining > 0;

            if (! $isFreeMessage && ! $creditManager->hasCredits($user)) {
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => "You're out of credits. Invite friends to earn more, or upgrade your plan to continue building your CV.",
                    'timestamp' => now()->toISOString(),
                    'is_error' => true,
                ];

                $this->dispatch('insufficient-credits');

                return;
            }

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

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $content,
                'timestamp' => now()->toISOString(),
            ];

            if (! $isFreeMessage && $response->usage) {
                $credits = $creditManager->calculateFromUsage($response->usage, 'ai_builder_message');
                $creditManager->deduct($user, $credits, 'ai_builder_message', null, [
                    'prompt_tokens' => $response->usage->promptTokens,
                    'completion_tokens' => $response->usage->completionTokens,
                    'conversation_id' => $this->conversationId,
                ]);
                $this->dispatch('credits-updated');
            }

            if ($this->cv && $this->cv->exists) {
                $this->cv->refresh();
                $this->dispatch('cv-updated', cvId: $this->cv->id);
            }
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => "I apologize, but I encountered an error processing your request. Please try again or rephrase your question.\n\nError: {$e->getMessage()}",
                'timestamp' => now()->toISOString(),
                'is_error' => true,
            ];
        }
    }

    public function quickPrompt(string $type): void
    {
        $prompts = [
            'improve_summary' => 'Help me write a compelling professional summary for a senior role in my industry.',
            'keywords' => 'What keywords should I include for a software engineering position?',
            'ats_check' => 'Review my CV and suggest ATS optimization improvements.',
            'job_match' => 'Analyze this job description and tell me what keywords I should add to my CV.',
            'template' => 'What CV template would work best for a senior position applying to startups?',
            'project' => 'Help me improve this project description with quantifiable achievements.',
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
    }

    public function render()
    {
        return view('livewire.cv-ai-chat');
    }
}
