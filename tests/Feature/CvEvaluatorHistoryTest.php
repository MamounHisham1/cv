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
