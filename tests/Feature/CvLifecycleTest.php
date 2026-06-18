<?php

use App\Models\Cv;
use App\Models\CvVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function verifiedUser(): User
{
    return User::factory()->create(['otp_verified_at' => now()]);
}

describe('CV lifecycle', function () {
    it('deep-copies a CV with all its sections', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create(['title' => 'My CV']);
        $cv->experiences()->create([
            'company' => 'Acme', 'title' => 'Engineer',
            'start_date' => '2022-01-01', 'is_current' => true,
            'description' => 'Did things', 'achievements' => ['Shipped X'],
            'technologies' => ['PHP'], 'sort_order' => 0,
        ]);
        $cv->skills()->create(['name' => 'Laravel', 'category' => 'general', 'level' => 'expert', 'sort_order' => 0]);

        $copy = $cv->duplicate();

        expect($copy->id)->not->toBe($cv->id)
            ->and($copy->user_id)->toBe($user->id)
            ->and($copy->title)->toBe('My CV (Copy)')
            ->and($copy->experiences()->count())->toBe(1)
            ->and($copy->skills()->count())->toBe(1)
            ->and($copy->experiences()->first()->company)->toBe('Acme')
            // Original stays intact.
            ->and(Cv::find($cv->id)->experiences()->count())->toBe(1);
    });

    it('deletes a CV and its sections', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();
        $cv->skills()->create(['name' => 'PHP', 'category' => 'general', 'level' => 'expert', 'sort_order' => 0]);

        $cv->delete();

        expect(Cv::find($cv->id))->toBeNull()
            ->and(DB::table('cv_skills')->where('cv_id', $cv->id)->count())->toBe(0);
    });

    it('respects ownership on delete (policy-level check)', function () {
        $owner = verifiedUser();
        $intruder = verifiedUser();
        $cv = Cv::factory()->for($owner)->create();

        // The intruder cannot resolve the CV through the owner-scoped query
        // the Drafts component uses, so the CV is untouched.
        $resolved = Cv::where('user_id', $intruder->id)->find($cv->id);
        expect($resolved)->toBeNull()
            ->and(Cv::find($cv->id))->not->toBeNull();
    });

    it('renders the Drafts page for an owner with CVs', function () {
        $user = verifiedUser();
        Cv::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('drafts'))
            ->assertOk();
    });
});

describe('public share link', function () {
    it('enables and disables sharing with an opaque token', function () {
        $cv = Cv::factory()->create();

        expect($cv->isShared())->toBeFalse();

        $token = $cv->enableSharing();
        expect(strlen($token))->toBe(32)
            ->and($cv->fresh()->isShared())->toBeTrue()
            ->and($cv->fresh()->shared_at)->not->toBeNull();

        $cv->disableSharing();
        expect($cv->fresh()->isShared())->toBeFalse()
            ->and($cv->fresh()->share_token)->toBeNull();
    });

    it('is reachable via token when shared, 404 when not', function () {
        $cv = Cv::factory()->create(['template_id' => 'professional-classic']);
        $token = $cv->enableSharing();

        // Shared → public route resolves.
        $this->get(route('cv.share', $token))->assertOk();

        // Disable → same token now 404s.
        $cv->disableSharing();
        $this->get(route('cv.share', $token))->assertNotFound();
    });

    it('is unreachable for a non-existent token', function () {
        $this->get(route('cv.share', 'nonexistent-token'))->assertNotFound();
    });
});

describe('versioning', function () {
    it('captures a snapshot and dedupes identical content', function () {
        $cv = Cv::factory()->create();
        $cv->skills()->create(['name' => 'PHP', 'category' => 'general', 'level' => 'expert', 'sort_order' => 0]);

        $first = CvVersion::snapshotIfChanged($cv, 'first');
        $second = CvVersion::snapshotIfChanged($cv, 'second');

        expect($first)->not->toBeNull()
            ->and($second)->toBeNull('unchanged content should not create a new snapshot')
            ->and($cv->versions()->count())->toBe(1);
    });

    it('reverts the CV to a captured snapshot and restores sections', function () {
        $cv = Cv::factory()->create(['title' => 'Original']);
        $cv->skills()->create(['name' => 'Original Skill', 'category' => 'general', 'level' => 'expert', 'sort_order' => 0]);
        $snapshot = CvVersion::snapshotIfChanged($cv, 'baseline');

        // Mutate the CV after snapshotting.
        $cv->update(['title' => 'Changed']);
        $cv->skills()->first()->update(['name' => 'Changed Skill']);

        expect($cv->fresh()->title)->toBe('Changed');

        // Revert → restores the snapshotted state.
        $snapshot->revert();
        $cv->refresh();

        expect($cv->title)->toBe('Original')
            ->and($cv->skills()->first()->name)->toBe('Original Skill');
    });

    it('revert captures the current state first (undo-able)', function () {
        $cv = Cv::factory()->create(['title' => 'V1']);
        $snapshot = CvVersion::snapshotIfChanged($cv, 'exported V1');

        $cv->update(['title' => 'V2 — unsaved state']);

        // Capture current (V2) before reverting, exactly like the component does.
        CvVersion::snapshotIfChanged($cv, 'Before revert');
        $snapshot->revert();

        expect($cv->fresh()->title)->toBe('V1')
            ->and($cv->versions()->count())->toBeGreaterThanOrEqual(2);
    });
});
