<?php

use App\Ai\Agents\InterviewerAgent;
use App\Ai\Agents\InterviewEvaluatorAgent;
use App\Livewire\AiInterviewer;
use App\Models\Cv;
use App\Models\InterviewSession;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = cloneUserWithCredits(50);
    $this->user->update(['otp_verified_at' => now()]);
    $this->cv = Cv::factory()->create(['user_id' => $this->user->id]);
    Config::set('credits.minimum_charge.ai_interview', 3);
});

function cloneUserWithCredits(int $credits)
{
    $user = User::factory()->create();
    $user->creditBalance()->create(['balance' => $credits, 'plan' => 'pro']);

    return $user;
}

it('renders the component for authenticated users', function () {
    actingAs($this->user)
        ->get(route('ai.interview'))
        ->assertOk()
        ->assertSee('AI Interviewer');
});

it('requires credits to start an interview', function () {
    $poorUser = cloneUserWithCredits(0);
    $cv = Cv::factory()->create(['user_id' => $poorUser->id]);

    actingAs($poorUser);

    Livewire::test(AiInterviewer::class)
        ->set('selectedCvId', $cv->id)
        ->set('interviewType', 'technical')
        ->call('startInterview')
        ->assertHasErrors(['credits']);

    expect(InterviewSession::count())->toBe(0);
});

it('starts an interview successfully and triggers AI', function () {
    actingAs($this->user);

    InterviewerAgent::fake(['Hello, let us start the interview.']);

    Livewire::test(AiInterviewer::class)
        ->set('selectedCvId', $this->cv->id)
        ->set('interviewType', 'behavioral')
        ->set('jobDescription', 'Senior Developer')
        ->call('startInterview')
        ->assertHasNoErrors()
        ->assertSet('state', 'active')
        ->assertDispatched('ai-responded');

    expect(InterviewSession::count())->toBe(1);

    $session = InterviewSession::first();
    expect($session->user_id)->toBe($this->user->id)
        ->and($session->cv_id)->toBe($this->cv->id)
        ->and($session->interview_type)->toBe('behavioral')
        ->and($session->job_description)->toBe('Senior Developer')
        ->and($session->status)->toBe('active')
        ->and($session->messages()->count())->toBe(1)
        ->and($session->messages()->first()->role)->toBe('interviewer');
});

it('can submit user answer and get AI reply', function () {
    actingAs($this->user);

    InterviewerAgent::fake([
        'First question',
        'Second question',
    ]);

    $component = Livewire::test(AiInterviewer::class)
        ->set('selectedCvId', $this->cv->id)
        ->call('startInterview');

    expect(InterviewSession::first()->messages()->count())->toBe(1);

    $component->call('submitAnswer', 'This is my answer.')
        ->assertDispatched('ai-responded');

    $session = InterviewSession::first();
    expect($session->messages()->count())->toBe(3); // First Q, User Ans, Second Q
});

it('ends interview and generates evaluation', function () {
    actingAs($this->user);

    InterviewerAgent::fake(['First question']);
    InterviewEvaluatorAgent::fake([
        [
            'overall_score' => 85,
            'grade' => 'A-',
            'summary' => 'Good performance',
            'criteria' => [
                'communication_clarity' => ['score' => 8, 'reason' => 'Good'],
                'technical_depth' => ['score' => 7, 'reason' => 'Okay'],
                'confidence_composure' => ['score' => 9, 'reason' => 'Great'],
                'star_method_usage' => ['score' => 8, 'reason' => 'Good'],
                'relevance_to_role' => ['score' => 8, 'reason' => 'Relevant'],
                'specificity_examples' => ['score' => 8, 'reason' => 'Specific'],
            ],
            'strengths' => ['Clear'],
            'improvements' => ['Details'],
        ],
    ]);

    $component = Livewire::test(AiInterviewer::class)
        ->set('selectedCvId', $this->cv->id)
        ->call('startInterview');

    $initialCredits = $this->user->creditBalance->balance;

    $component->call('endInterview')
        ->assertSet('state', 'evaluating');

    // Check credit deduction (minimum charge for interview is 3)
    expect($this->user->creditBalance->fresh()->balance)->toBe($initialCredits - 3);

    $session = InterviewSession::first();
    expect($session->status)->toBe('completed');

    $component->call('generateEvaluation')
        ->assertSet('state', 'results');

    expect($session->evaluation()->count())->toBe(1);

    $evaluation = $session->evaluation()->first();
    expect($evaluation->overall_score)->toBe(85)
        ->and($evaluation->grade)->toBe('A-');
});
