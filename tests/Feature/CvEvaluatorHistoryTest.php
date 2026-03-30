<?php

use App\Livewire\CvEvaluator;
use App\Models\CvEvaluation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Evaluation History', function () {
    it('loads evaluation history on mount', function () {
        CvEvaluation::factory()->count(3)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->assertSet('evaluations', function ($evaluations) {
                return count($evaluations) === 3;
            });
    });

    it('loads evaluations ordered by newest first', function () {
        $old = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 50,
            'created_at' => now()->subDays(5),
        ]);
        $new = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 80,
            'created_at' => now(),
        ]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->assertSet('evaluations.0.id', $new->id)
            ->assertSet('evaluations.1.id', $old->id);
    });

    it('shows empty evaluations for guest', function () {
        Livewire::test(CvEvaluator::class)
            ->assertSet('evaluations', []);
    });

    it('only loads current users evaluations', function () {
        $otherUser = User::factory()->create();
        CvEvaluation::factory()->create(['user_id' => $otherUser->id]);
        CvEvaluation::factory()->count(2)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->assertSet('evaluations', function ($evaluations) {
                return count($evaluations) === 2;
            });
    });
});

describe('Evaluation Comparison', function () {
    it('toggles evaluation selection for comparison', function () {
        $evals = CvEvaluation::factory()->count(3)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->assertSet('selectedEvaluationIds', [$evals[0]->id])
            ->call('toggleEvaluationSelection', $evals[1]->id)
            ->assertSet('selectedEvaluationIds', [$evals[0]->id, $evals[1]->id]);
    });

    it('deselects evaluation on second toggle', function () {
        $evals = CvEvaluation::factory()->count(2)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->assertSet('selectedEvaluationIds', []);
    });

    it('limits selection to two evaluations', function () {
        $evals = CvEvaluation::factory()->count(4)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->call('toggleEvaluationSelection', $evals[1]->id)
            ->call('toggleEvaluationSelection', $evals[2]->id)
            ->assertSet('selectedEvaluationIds', [$evals[0]->id, $evals[1]->id]);
    });

    it('computes comparison diff between two evaluations', function () {
        $evalA = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 60,
            'criteria' => [
                'contact_information' => ['score' => 6, 'reason' => 'Good'],
                'work_experience' => ['score' => 5, 'reason' => 'Average'],
            ],
        ]);
        $evalB = CvEvaluation::factory()->create([
            'user_id' => $this->user->id,
            'overall_score' => 75,
            'criteria' => [
                'contact_information' => ['score' => 8, 'reason' => 'Excellent'],
                'work_experience' => ['score' => 7, 'reason' => 'Improved'],
            ],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evalA->id)
            ->call('toggleEvaluationSelection', $evalB->id)
            ->assertSet('comparisonResult', function ($result) {
                return $result['overall_diff'] === 15
                    && $result['criteria_diffs']['contact_information'] === 2
                    && $result['criteria_diffs']['work_experience'] === 2;
            });
    });

    it('clears comparison when deselecting', function () {
        $evals = CvEvaluation::factory()->count(2)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(CvEvaluator::class)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->call('toggleEvaluationSelection', $evals[1]->id)
            ->call('toggleEvaluationSelection', $evals[0]->id)
            ->assertSet('comparisonResult', null);
    });

    it('reloads evaluations after refresh called', function () {
        CvEvaluation::factory()->create(['user_id' => $this->user->id]);

        $component = Livewire::actingAs($this->user)
            ->test(CvEvaluator::class);

        $initialCount = count($component->get('evaluations'));

        CvEvaluation::factory()->create(['user_id' => $this->user->id]);

        $component->call('refreshEvaluations')
            ->assertSet('evaluations', function ($evaluations) use ($initialCount) {
                return count($evaluations) === $initialCount + 1;
            });
    });
});
