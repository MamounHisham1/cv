<?php

namespace App\Ai\Agents;

use App\Models\Cv;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Temperature(0.0)]
#[MaxTokens(4096)]
#[Timeout(300)]
class InterviewEvaluatorAgent implements Agent
{
    use Promptable;

    public function __construct(
        protected Cv $cv,
        protected array $transcript,
        protected ?string $jobDescription = null,
    ) {}

    public function instructions(): Stringable|string
    {
        $transcriptText = collect($this->transcript)->map(function ($message) {
            return ucfirst($message['role']).': '.$message['content'];
        })->join("\n\n");

        $jobContext = $this->jobDescription
            ? "Job Description:\n{$this->jobDescription}"
            : 'No specific job description provided.';

        return <<<INSTRUCTIONS
        You are an expert technical interviewer and recruiter evaluating a mock interview transcript.

        ## Candidate Context
        Target role: {$this->cv->title}

        {$jobContext}

        ## Interview Transcript
        {$transcriptText}

        ## Task

        Evaluate the candidate across exactly 6 criteria. For each, assign a score from 0 to 10 and a one-sentence reason.

        Criteria:
        1. Communication Clarity
        2. Technical Depth
        3. Confidence and Composure
        4. STAR Method Usage
        5. Relevance to Role
        6. Specificity of Examples

        Also provide: an overall score (0-100), a letter grade (A, B, C, D, or F), a 2-3 sentence summary, top 3 strengths, and top 3 areas for improvement.

        ## Output Format

        You MUST respond with valid JSON only. No markdown, no commentary outside the JSON. Use this exact structure:

        ```json
        {
            "overall_score": 45,
            "grade": "D",
            "summary": "2-3 sentence summary here.",
            "communication_clarity_score": 3,
            "communication_clarity_reason": "One sentence reason.",
            "technical_depth_score": 2,
            "technical_depth_reason": "One sentence reason.",
            "confidence_composure_score": 4,
            "confidence_composure_reason": "One sentence reason.",
            "star_method_score": 1,
            "star_method_reason": "One sentence reason.",
            "relevance_role_score": 2,
            "relevance_role_reason": "One sentence reason.",
            "specificity_examples_score": 1,
            "specificity_examples_reason": "One sentence reason.",
            "strengths": "Strength 1||Strength 2||Strength 3",
            "improvements": "Improvement 1||Improvement 2||Improvement 3"
        }
        ```
        INSTRUCTIONS;
    }
}
