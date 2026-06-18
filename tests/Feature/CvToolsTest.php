<?php

use App\Ai\Tools\AddCvCertification;
use App\Ai\Tools\DeleteCvExperience;
use App\Ai\Tools\DeleteCvLanguage;
use App\Ai\Tools\ReadCvData;
use App\Ai\Tools\UpdateCvExperience;
use App\Models\Cv;
use App\Models\CvExperience;
use App\Models\CvLanguage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Tools\Request;

uses(RefreshDatabase::class);

function makeRequest(array $args): Request
{
    return new Request($args);
}

it('reads cv data with item ids for targeting', function () {
    $cv = Cv::factory()->create();
    $exp = CvExperience::factory()->for($cv)->create(['title' => 'Engineer', 'company' => 'Acme']);
    $lang = CvLanguage::create(['cv_id' => $cv->id, 'language' => 'English', 'proficiency' => 'fluent', 'sort_order' => 0]);

    $tool = (new ReadCvData)->setCv($cv);

    $result = $tool->handle(makeRequest([]));

    expect($result)->toBeString()
        ->and($result)->toContain("[id:{$exp->id}]")
        ->and($result)->toContain("[id:{$lang->id}]")
        ->and($result)->toContain('confirm with the user before deleting');
});

it('updates only the provided fields on an experience', function () {
    $cv = Cv::factory()->create();
    $exp = CvExperience::factory()->for($cv)->create(['title' => 'Dev', 'company' => 'Acme']);

    $tool = (new UpdateCvExperience)->setCv($cv);

    $result = $tool->handle(makeRequest(['id' => $exp->id, 'title' => 'Senior Dev']));

    expect($result)->toContain('updated')
        ->and($exp->refresh()->title)->toBe('Senior Dev')
        ->and($exp->company)->toBe('Acme');
});

it('refuses to update an experience without an id', function () {
    $cv = Cv::factory()->create();

    $tool = (new UpdateCvExperience)->setCv($cv);

    $result = $tool->handle(makeRequest(['title' => 'Dev']));

    expect($result)->toContain('No experience id provided');
});

it('refuses to update an experience belonging to another cv', function () {
    $cvA = Cv::factory()->create();
    $cvB = Cv::factory()->create();
    $exp = CvExperience::factory()->for($cvB)->create();

    $tool = (new UpdateCvExperience)->setCv($cvA);

    $result = $tool->handle(makeRequest(['id' => $exp->id, 'title' => 'Hacked']));

    expect($result)->toContain("No experience found with id {$exp->id}")
        ->and($exp->refresh()->title)->not->toBe('Hacked');
});

it('deletes an experience and resequences sort_order', function () {
    $cv = Cv::factory()->create();
    $first = CvExperience::factory()->for($cv)->create(['sort_order' => 0]);
    $second = CvExperience::factory()->for($cv)->create(['sort_order' => 1]);

    $tool = (new DeleteCvExperience)->setCv($cv);

    $result = $tool->handle(makeRequest(['id' => $first->id]));

    expect($result)->toContain('Deleted')
        ->and(CvExperience::find($first->id))->toBeNull()
        ->and($second->refresh()->sort_order)->toBe(0);
});

it('adds a certification', function () {
    $cv = Cv::factory()->create();

    $tool = (new AddCvCertification)->setCv($cv);

    $result = $tool->handle(makeRequest([
        'name' => 'AWS Certified Solutions Architect',
        'issuing_organization' => 'Amazon Web Services',
        'issue_date' => '2024-01-15',
    ]));

    expect($result)->toContain('AWS Certified Solutions Architect')
        ->and($cv->certifications()->count())->toBe(1)
        ->and($cv->certifications()->first()->is_aws_certification)->toBeTrue();
});

it('deletes a language', function () {
    $cv = Cv::factory()->create();
    $lang = CvLanguage::create(['cv_id' => $cv->id, 'language' => 'French', 'proficiency' => 'native', 'sort_order' => 0]);

    $tool = (new DeleteCvLanguage)->setCv($cv);

    $result = $tool->handle(makeRequest(['id' => $lang->id]));

    expect($result)->toContain('French')
        ->and(CvLanguage::find($lang->id))->toBeNull();
});

it('refuses deletion when no cv is set', function () {
    $tool = new DeleteCvExperience;

    $result = $tool->handle(makeRequest(['id' => 1]));

    expect($result)->toContain('No CV found');
});
