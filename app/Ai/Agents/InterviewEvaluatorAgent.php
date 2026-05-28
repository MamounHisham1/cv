<?php

namespace App\Ai\Agents;

use App\Models\Cv;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::Ollama)]
#[Temperature(0.0)]
class InterviewEvaluatorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        protected Cv $cv,
        protected array $transcript,
        protected ?string $jobDescription = null
    ) {}

    public function instructions(): string
    {
        $transcriptText = collect($this->transcript)->map(function ($message) {
            return ucfirst($message['role']).': '.$message['content'];
        })->join("\n\n");

        return <<<PROMPT
            You are an expert technical interviewer and recruiter.
            Your task is to evaluate a candidate based on their mock interview transcript.
            
            Evaluate the candidate on the following criteria out of 10:
            1. Communication Clarity
            2. Technical Depth (if applicable)
            3. Confidence & Composure
            4. Use of STAR method (Situation, Task, Action, Result) for behavioral questions
            5. Relevance to the role
            6. Specificity of examples
            
            Provide a constructive overall summary, grade (e.g. A, B+, C), and highlight their main strengths and areas for improvement.
            
            Job Description Context:
            {$this->jobDescription}
            
            Interview Transcript:
            {$transcriptText}
            PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'overall_score' => $schema->integer()->min(0)->max(100)->required()->description('Overall score out of 100'),
            'grade' => $schema->string()->required()->description('Letter grade (e.g. A, B, C)'),
            'summary' => $schema->string()->required()->description('A brief 2-3 sentence summary of the performance'),
            'criteria' => $schema->object([
                'communication_clarity' => $schema->object([
                    'score' => $schema->integer()->min(1)->max(10)->required(),
                    'reason' => $schema->string()->required(),
                ])->required(),
                'technical_depth' => $schema->object([
                    'score' => $schema->integer()->min(1)->max(10)->required(),
                    'reason' => $schema->string()->required(),
                ])->required(),
                'confidence_composure' => $schema->object([
                    'score' => $schema->integer()->min(1)->max(10)->required(),
                    'reason' => $schema->string()->required(),
                ])->required(),
                'star_method_usage' => $schema->object([
                    'score' => $schema->integer()->min(1)->max(10)->required(),
                    'reason' => $schema->string()->required(),
                ])->required(),
                'relevance_to_role' => $schema->object([
                    'score' => $schema->integer()->min(1)->max(10)->required(),
                    'reason' => $schema->string()->required(),
                ])->required(),
                'specificity_examples' => $schema->object([
                    'score' => $schema->integer()->min(1)->max(10)->required(),
                    'reason' => $schema->string()->required(),
                ])->required(),
            ])->required(),
            'strengths' => $schema->array(
                $schema->string()
            )->required()->description('List of key strengths demonstrated'),
            'improvements' => $schema->array(
                $schema->string()
            )->required()->description('List of key areas for improvement'),
        ];
    }
}
