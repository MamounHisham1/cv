<?php

namespace App\Livewire;

use App\Ai\Agents\CvBuilderAgent;
use App\Models\Cv;
use Illuminate\Support\Facades\Auth;
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

        // Add user message
        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
            'timestamp' => now()->toISOString(),
        ];

        $this->isLoading = true;

        // Get AI response
        $this->getAiResponse($message);

        $this->isLoading = false;
    }

    private function getAiResponse(string $message): void
    {
        try {
            $agent = new CvBuilderAgent($this->cv);

            if ($this->conversationId) {
                $agent = $agent->continue($this->conversationId, as: Auth::user());
            } else {
                $agent = $agent->forUser(Auth::user());
            }

            $response = $agent->prompt($message);

            $this->conversationId = $response->conversationId;

            $content = (string) $response;

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $content,
                'timestamp' => now()->toISOString(),
            ];
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
