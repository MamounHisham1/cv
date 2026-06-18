<?php

use App\CoverLetterTemplates;
use App\Models\CoverLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function verifiedUser(): User
{
    return User::factory()->create(['otp_verified_at' => now()]);
}

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
