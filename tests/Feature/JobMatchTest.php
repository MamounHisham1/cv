<?php

use App\Jobs\ProcessJobMatch;
use App\Livewire\JobMatch;
use App\Models\Cv;
use App\Models\CvJobMatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('CvJobMatch model', function () {
    it('casts json columns and tracks status', function () {
        $match = CvJobMatch::factory()->create([
            'matched_keywords' => ['Laravel', 'PHP'],
            'missing_keywords' => ['Kubernetes'],
        ]);

        expect($match->matched_keywords)->toBe(['Laravel', 'PHP'])
            ->and($match->missing_keywords)->toBe(['Kubernetes'])
            ->and($match->isCompleted())->toBeTrue()
            ->and($match->compatibility_score)->toBeInt();
    });

    it('has pending and failed states', function () {
        $pending = CvJobMatch::factory()->pending()->create();
        $failed = CvJobMatch::factory()->failed()->create();

        expect($pending->isPending())->toBeTrue()
            ->and($pending->compatibility_score)->toBeNull()
            ->and($failed->isFailed())->toBeTrue()
            ->and($failed->error_message)->toBe('test failure');
    });
});

describe('JobMatch page', function () {
    it('renders for a user with CVs', function () {
        $user = verifiedUser();
        Cv::factory()->for($user)->create();

        $this->actingAs($user)->get(route('job.match'))->assertOk();
    });

    it('requires a job description to run a match', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();

        Livewire::actingAs($user)->test(JobMatch::class)
            ->set('selectedCvId', $cv->id)
            ->set('jobDescription', 'too short')
            ->call('runMatch')
            ->assertHasErrors(['jobDescription']);
    });

    it('dispatches the ProcessJobMatch job and creates a pending record', function () {
        Queue::fake();
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();

        // Give the user enough credits.
        $user->creditBalance()->create(['balance' => 100, 'plan' => 'pro']);

        Livewire::actingAs($user)->test(JobMatch::class)
            ->set('selectedCvId', $cv->id)
            ->set('jobTitle', 'Backend Engineer')
            ->set('jobDescription', str_repeat('We need a backend engineer with Laravel experience and strong database skills. ', 5))
            ->call('runMatch');

        Queue::assertPushed(ProcessJobMatch::class);

        $match = CvJobMatch::where('cv_id', $cv->id)->first();
        expect($match)->not->toBeNull()
            ->and($match->status)->toBe(CvJobMatch::STATUS_PENDING)
            ->and($match->job_title)->toBe('Backend Engineer');
    });

    it('blocks running a match when the user lacks credits', function () {
        Queue::fake();
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();
        // No creditBalance row → balance is effectively 0.
        $user->creditBalance()->create(['balance' => 0, 'plan' => 'free']);

        Livewire::actingAs($user)->test(JobMatch::class)
            ->set('selectedCvId', $cv->id)
            ->set('jobDescription', str_repeat('Senior engineer role requiring strong system design. ', 5))
            ->call('runMatch');

        Queue::assertNotPushed(ProcessJobMatch::class);
        expect(CvJobMatch::count())->toBe(0);
    });

    it('does not run a match against another user\'s CV', function () {
        Queue::fake();
        $owner = verifiedUser();
        $intruder = verifiedUser();
        $cv = Cv::factory()->for($owner)->create();

        Livewire::actingAs($intruder)->test(JobMatch::class)
            ->set('selectedCvId', $cv->id)
            ->set('jobDescription', str_repeat('Senior engineer role requiring strong system design. ', 5))
            ->call('runMatch');

        Queue::assertNotPushed(ProcessJobMatch::class);
        expect(CvJobMatch::where('cv_id', $cv->id)->exists())->toBeFalse();
    });

    it('transitions to complete when the match record is completed', function () {
        $user = verifiedUser();
        $match = CvJobMatch::factory()->for($user)->create(); // default = completed

        // Simulate the poll finding a completed record.
        $component = Livewire::actingAs($user)->test(JobMatch::class)
            ->call('viewResult', $match->id);

        expect($component->get('state'))->toBe('complete');
    });
});
