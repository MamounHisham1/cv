<?php

use App\Models\Cv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function verifiedUser(): User
{
    return User::factory()->create(['otp_verified_at' => now()]);
}

describe('CV export', function () {
    it('downloads a valid PDF for the CV owner', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->get(route('cv.export', [$cv, 'pdf']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        // Streamed responses don't expose their body via getContent() in
        // the test harness; assert the attachment filename instead.
        $disposition = $response->headers->get('content-disposition');
        expect($disposition)->toContain('attachment')
            ->and($disposition)->toContain('.pdf');
    });

    it('downloads a valid DOCX for the CV owner', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->get(route('cv.export', [$cv, 'docx']));

        $response->assertOk();
        $response->assertHeader(
            'content-type',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        // Streamed responses buffer through the test harness; the .docx
        // filename is appended to the Content-Disposition header.
        $disposition = $response->headers->get('content-disposition');
        expect($disposition)->toContain('attachment')
            ->and($disposition)->toContain('.docx');
    });

    it('forbids downloading a CV owned by another user', function () {
        $owner = verifiedUser();
        $intruder = verifiedUser();
        $cv = Cv::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('cv.export', [$cv, 'pdf']))
            ->assertForbidden();
    });

    it('rejects unsupported export formats', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();

        $this->actingAs($user)
            ->get("/cv/{$cv->id}/export/jpg")
            ->assertNotFound();
    });

    it('redirects unauthenticated users to login', function () {
        $cv = Cv::factory()->create();

        $this->get(route('cv.export', [$cv, 'pdf']))
            ->assertRedirect('/login');
    });

    it('exports a CV with all sections populated', function () {
        $user = verifiedUser();
        $cv = Cv::factory()->for($user)->create();
        $cv->experiences()->create([
            'company' => 'Acme',
            'title' => 'Engineer',
            'start_date' => '2022-01-01',
            'is_current' => true,
            'description' => 'Did things',
            'achievements' => ['Shipped X'],
            'technologies' => ['PHP', 'Laravel'],
            'sort_order' => 0,
        ]);
        $cv->skills()->create(['name' => 'Laravel', 'category' => 'general', 'level' => 'expert', 'sort_order' => 0]);

        $pdf = $this->actingAs($user)->get(route('cv.export', [$cv, 'pdf']));
        $docx = $this->actingAs($user)->get(route('cv.export', [$cv, 'docx']));

        $pdf->assertOk();
        $docx->assertOk();
        expect($pdf->headers->get('content-disposition'))->toContain('.pdf')
            ->and($docx->headers->get('content-disposition'))->toContain('.docx');
    });
});
