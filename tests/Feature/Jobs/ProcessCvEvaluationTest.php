<?php

use App\Ai\Agents\CvEvaluatorAgent;
use App\Jobs\ProcessCvEvaluation;
use App\Models\CvEvaluation;
use App\Models\User;
use App\Notifications\EvaluationCompletedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();

    $this->user = User::factory()->create();
    $this->user->creditBalance()->create(['balance' => 50, 'plan' => 'pro']);
});

it('transitions the evaluation pending -> processing -> completed and stores the AI payload', function () {
    $evaluation = CvEvaluation::factory()->create([
        'user_id' => $this->user->id,
        'status' => CvEvaluation::STATUS_PENDING,
        'cv_text' => 'A sample CV text.',
    ]);

    CvEvaluatorAgent::fake([[
        'overall_score' => 82,
        'grade' => 'B+',
        'summary' => 'A strong CV with room for improvement.',
        'contact_information_score' => 9,
        'contact_information_reason' => 'Complete.',
        'professional_summary_score' => 8,
        'professional_summary_reason' => 'Clear.',
        'work_experience_score' => 8,
        'work_experience_reason' => 'Good.',
        'skills_section_score' => 7,
        'skills_section_reason' => 'Okay.',
        'education_score' => 8,
        'education_reason' => 'Fine.',
        'ats_compatibility_score' => 9,
        'ats_compatibility_reason' => 'ATS friendly.',
        'formatting_readability_score' => 8,
        'formatting_readability_reason' => 'Readable.',
        'achievements_impact_score' => 7,
        'achievements_impact_reason' => 'Some metrics.',
        'keyword_optimisation_score' => 8,
        'keyword_optimisation_reason' => 'Keywords present.',
        'overall_completeness_score' => 8,
        'overall_completeness_reason' => 'Complete.',
        'top_strengths' => 'Contact info||ATS layout',
        'critical_improvements' => 'Add more metrics',
    ]]);

    (new ProcessCvEvaluation(
        userId: $this->user->id,
        cvText: 'A sample CV text.',
        filename: 'sample.cv',
        inputMode: 'paste',
        cvId: null,
        evaluationId: $evaluation->id,
    ))->handle();

    $evaluation->refresh();

    expect($evaluation->status)->toBe(CvEvaluation::STATUS_COMPLETED)
        ->and($evaluation->overall_score)->toBe(82)
        ->and($evaluation->grade)->toBe('B+')
        ->and($evaluation->criteria)->toHaveKey('contact_information')
        ->and($evaluation->criteria['contact_information']['score'])->toBe(9)
        ->and($evaluation->top_strengths)->toContain('Contact info');

    Notification::assertSentTo($this->user, EvaluationCompletedNotification::class);

    // Credits were deducted (the credit deduction happens inside the job).
    expect($this->user->fresh()->creditBalance->balance)->toBeLessThan(50);
});

it('marks the evaluation as failed when the AI agent throws', function () {
    $evaluation = CvEvaluation::factory()->create([
        'user_id' => $this->user->id,
        'status' => CvEvaluation::STATUS_PENDING,
        'cv_text' => 'broken',
    ]);

    // Force the evaluator to throw on every prompt.
    CvEvaluatorAgent::fake(fn () => throw new RuntimeException('AI gateway down'));

    (new ProcessCvEvaluation(
        userId: $this->user->id,
        cvText: 'broken',
        filename: 'broken.cv',
        inputMode: 'paste',
        cvId: null,
        evaluationId: $evaluation->id,
    ))->handle();

    expect($evaluation->fresh()->status)->toBe(CvEvaluation::STATUS_FAILED)
        ->and($evaluation->fresh()->error_message)->not->toBeNull();
});

it('transitions an existing evaluation from pending through processing to completed', function () {
    // Confirm the job itself flips status to processing before the AI call.
    $evaluation = CvEvaluation::factory()->create([
        'user_id' => $this->user->id,
        'status' => CvEvaluation::STATUS_PENDING,
        'cv_text' => 'transition test',
    ]);

    CvEvaluatorAgent::fake([[
        'overall_score' => 70,
        'grade' => 'C+',
        'summary' => 'Average.',
        'contact_information_score' => 5,
        'contact_information_reason' => 'r',
        'professional_summary_score' => 5,
        'professional_summary_reason' => 'r',
        'work_experience_score' => 5,
        'work_experience_reason' => 'r',
        'skills_section_score' => 5,
        'skills_section_reason' => 'r',
        'education_score' => 5,
        'education_reason' => 'r',
        'ats_compatibility_score' => 5,
        'ats_compatibility_reason' => 'r',
        'formatting_readability_score' => 5,
        'formatting_readability_reason' => 'r',
        'achievements_impact_score' => 5,
        'achievements_impact_reason' => 'r',
        'keyword_optimisation_score' => 5,
        'keyword_optimisation_reason' => 'r',
        'overall_completeness_score' => 5,
        'overall_completeness_reason' => 'r',
        'top_strengths' => 'x',
        'critical_improvements' => 'y',
    ]]);

    (new ProcessCvEvaluation(
        userId: $this->user->id,
        cvText: 'transition test',
        filename: null,
        inputMode: 'paste',
        cvId: null,
        evaluationId: $evaluation->id,
    ))->handle();

    // End-state is completed; the intermediate "processing" was written first.
    expect($evaluation->fresh()->status)->toBe(CvEvaluation::STATUS_COMPLETED);
});
