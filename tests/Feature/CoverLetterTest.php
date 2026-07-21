<?php

use App\CoverLetterTemplates;
use App\Jobs\ProcessCoverLetter;
use App\Livewire\CoverLetterBuilder;
use App\Models\CoverLetter;
use App\Models\Cv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('cover letter templates registry', function () {
    it('exposes the three bundled templates', function () {
        $all = CoverLetterTemplates::all();

        expect($all)->toHaveKeys(['classic', 'modern', 'concise'])
            ->and(CoverLetterTemplates::default())->toBe('classic')
            ->and(CoverLetterTemplates::exists('classic'))->toBeTrue()
            ->and(CoverLetterTemplates::exists('nope'))->toBeFalse()
            ->and(CoverLetterTemplates::name('modern'))->toBe('Modern');
    });

    it('options returns slug => name pairs', function () {
        $options = CoverLetterTemplates::options();

        expect($options)->toBe(['classic' => 'Classic', 'modern' => 'Modern', 'concise' => 'Concise']);
    });
});

describe('cover letter model', function () {
    it('belongs to a user and an optional cv', function () {
        $letter = CoverLetter::factory()->create();

        expect($letter->user)->not->toBeNull()
            ->and($letter->cv)->not->toBeNull();
    });

    it('can be created standalone (no source cv)', function () {
        $letter = CoverLetter::factory()->standalone()->create();

        expect($letter->cv_id)->toBeNull()
            ->and($letter->cv)->toBeNull();
    });

    it('derives sender name from the source cv when present', function () {
        $letter = CoverLetter::factory()->create();
        $letter->cv->update(['personal_info' => ['first_name' => 'Jane', 'last_name' => 'Doe']]);

        expect($letter->fresh()->sender_name)->toBe('Jane Doe');
    });

    it('falls back to the letter title when there is no source cv', function () {
        $letter = CoverLetter::factory()->standalone()->create(['title' => 'My Letter']);

        expect($letter->sender_name)->toBe('My Letter');
    });

    it('tracks generation status', function () {
        $generating = CoverLetter::factory()->create(['status' => CoverLetter::STATUS_GENERATING]);
        $done = CoverLetter::factory()->create(['status' => CoverLetter::STATUS_GENERATED]);
        $failed = CoverLetter::factory()->create(['status' => CoverLetter::STATUS_FAILED]);

        expect($generating->isGenerating())->toBeTrue()
            ->and($done->isGenerated())->toBeTrue()
            ->and($failed->isFailed())->toBeTrue()
            ->and(CoverLetter::factory()->create()->isDraft())->toBeTrue();
    });
});

describe('async AI generation', function () {
    it('dispatches the job and creates a generating letter', function () {
        Queue::fake();
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();
        $user->creditBalance()->create(['balance' => 100, 'plan' => 'pro']);

        Livewire::actingAs($user)->test(CoverLetterBuilder::class)
            ->set('generateCvId', $cv->id)
            ->set('jobDescription', 'Senior Laravel engineer role at a fast-growing startup.')
            ->call('startGeneration');

        Queue::assertPushed(ProcessCoverLetter::class);

        $letter = CoverLetter::where('cv_id', $cv->id)->first();
        expect($letter)->not->toBeNull()
            ->and($letter->status)->toBe(CoverLetter::STATUS_GENERATING)
            ->and($letter->job_description)->toBe('Senior Laravel engineer role at a fast-growing startup.');
    });

    it('requires a CV to start generation', function () {
        $user = verifiedUser();

        Livewire::actingAs($user)->test(CoverLetterBuilder::class)
            ->call('startGeneration')
            ->assertHasErrors(['generateCvId']);
    });

    it('blocks generation without enough credits', function () {
        Queue::fake();
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();
        $user->creditBalance()->create(['balance' => 0, 'plan' => 'free']);

        Livewire::actingAs($user)->test(CoverLetterBuilder::class)
            ->set('generateCvId', $cv->id)
            ->call('startGeneration');

        Queue::assertNotPushed(ProcessCoverLetter::class);
        expect(CoverLetter::count())->toBe(0);
    });

    it('transitions to complete when the letter finishes generating', function () {
        $user = verifiedUser();
        $letter = CoverLetter::factory()->for($user)->create([
            'status' => CoverLetter::STATUS_GENERATED,
            'body' => 'Dear Hiring Manager, …',
        ]);

        $component = Livewire::actingAs($user)->test(CoverLetterBuilder::class)
            ->call('edit', $letter->id);

        expect($component->get('body'))->toBe('Dear Hiring Manager, …');
    });
});

describe('cover letter export', function () {
    it('downloads a PDF for the owner', function () {
        $user = verifiedUser();
        $letter = CoverLetter::factory()->for($user)->create(['template_id' => 'classic']);

        $response = $this->actingAs($user)
            ->get(route('cover-letters.export', [$letter, 'pdf']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        expect($response->headers->get('content-disposition'))->toContain('.pdf');
    });

    it('downloads a DOCX for the owner', function () {
        $user = verifiedUser();
        $letter = CoverLetter::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->get(route('cover-letters.export', [$letter, 'docx']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        expect($response->headers->get('content-disposition'))->toContain('.docx');
    });

    it('forbids export for non-owners', function () {
        $owner = verifiedUser();
        $intruder = verifiedUser();
        $letter = CoverLetter::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('cover-letters.export', [$letter, 'pdf']))
            ->assertForbidden();
    });

    it('rejects unsupported formats', function () {
        $user = verifiedUser();
        $letter = CoverLetter::factory()->for($user)->create();

        $this->actingAs($user)
            ->get("/cover-letters/{$letter->id}/export/jpg")
            ->assertNotFound();
    });
});
