<?php

use App\Models\CvEvaluation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('evaluations:vectorize command', function () {
    it('has the correct signature', function () {
        $this->artisan('evaluations:vectorize', ['--dry-run' => true])
            ->assertSuccessful();
    });

    it('reports count in dry-run mode without processing', function () {
        $user = User::factory()->create();
        CvEvaluation::factory()->count(3)->create(['user_id' => $user->id]);

        $this->artisan('evaluations:vectorize', ['--dry-run' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('3 evaluation(s) would be vectorized');
    });

    it('reports zero evaluations when none exist', function () {
        $this->artisan('evaluations:vectorize', ['--dry-run' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('0 evaluation(s) would be vectorized');
    });
});
