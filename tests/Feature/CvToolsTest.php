<?php

use App\Ai\Tools\AddCvCertification;
use App\Ai\Tools\AskClarifyingQuestions;
use App\Ai\Tools\DeleteCvExperience;
use App\Ai\Tools\DeleteCvLanguage;
use App\Ai\Tools\ReadCvData;
use App\Ai\Tools\UpdateCvExperience;
use App\Ai\Tools\UpdateCvSummary;
use App\Models\Cv;
use App\Models\CvExperience;
use App\Models\CvLanguage;
use App\Services\PendingClarifications;
use App\Services\ProposedCvChanges;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Tools\Request;

uses(RefreshDatabase::class);

if (! function_exists('makeRequest')) {
    function makeRequest(array $args): Request
    {
        return new Request($args);
    }
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

it('stages an experience update without mutating the CV yet', function () {
    $cv = Cv::factory()->create();
    $exp = CvExperience::factory()->for($cv)->create(['title' => 'Dev', 'company' => 'Acme']);

    $proposed = app(ProposedCvChanges::class);
    $proposed->clear();

    $tool = (new UpdateCvExperience)->setCv($cv);

    $result = $tool->handle(makeRequest(['id' => $exp->id, 'title' => 'Senior Dev']));

    // The change is staged for review, NOT applied — the record is untouched.
    expect($result)->toContain('STAGED for review (NOT applied)')
        ->and($exp->refresh()->title)->toBe('Dev')           // unchanged
        ->and($exp->company)->toBe('Acme')                    // unchanged
        ->and($proposed->has())->toBeTrue()
        ->and($proposed->all())->toHaveCount(1)
        ->and($proposed->all()[0]['action'])->toBe('update')
        ->and($proposed->all()[0]['after']['title'])->toBe('Senior Dev');
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

it('stages an experience deletion without removing it yet', function () {
    $cv = Cv::factory()->create();
    $first = CvExperience::factory()->for($cv)->create(['sort_order' => 0]);
    $second = CvExperience::factory()->for($cv)->create(['sort_order' => 1]);

    $proposed = app(ProposedCvChanges::class);
    $proposed->clear();

    $tool = (new DeleteCvExperience)->setCv($cv);

    $result = $tool->handle(makeRequest(['id' => $first->id]));

    // Staged for review — the record still exists and sort_order is untouched.
    expect($result)->toContain('STAGED for review (NOT applied)')
        ->and(CvExperience::find($first->id))->not->toBeNull()
        ->and($second->refresh()->sort_order)->toBe(1)
        ->and($proposed->has())->toBeTrue()
        ->and($proposed->all()[0]['action'])->toBe('delete')
        ->and($proposed->all()[0]['record_id'])->toBe($first->id);
});

it('stages a certification addition without creating it yet', function () {
    $cv = Cv::factory()->create();

    $proposed = app(ProposedCvChanges::class);
    $proposed->clear();

    $tool = (new AddCvCertification)->setCv($cv);

    $result = $tool->handle(makeRequest([
        'name' => 'AWS Certified Solutions Architect',
        'issuing_organization' => 'Amazon Web Services',
        'issue_date' => '2024-01-15',
    ]));

    // Staged for review — nothing written to the DB yet.
    expect($result)->toContain('STAGED for review (NOT applied)')
        ->and($cv->certifications()->count())->toBe(0)
        ->and($proposed->has())->toBeTrue()
        ->and($proposed->all()[0]['action'])->toBe('create')
        ->and($proposed->all()[0]['after']['name'])->toBe('AWS Certified Solutions Architect');
});

it('stages a language deletion without removing it yet', function () {
    $cv = Cv::factory()->create();
    $lang = CvLanguage::create(['cv_id' => $cv->id, 'language' => 'French', 'proficiency' => 'native', 'sort_order' => 0]);

    $proposed = app(ProposedCvChanges::class);
    $proposed->clear();

    $tool = (new DeleteCvLanguage)->setCv($cv);

    $result = $tool->handle(makeRequest(['id' => $lang->id]));

    expect($result)->toContain('STAGED for review (NOT applied)')
        ->and(CvLanguage::find($lang->id))->not->toBeNull()
        ->and($proposed->all()[0]['action'])->toBe('delete');
});

it('stages a summary rewrite without mutating the CV yet', function () {
    $cv = Cv::factory()->create(['summary' => 'Old summary text.']);

    $proposed = app(ProposedCvChanges::class);
    $proposed->clear();

    $tool = (new UpdateCvSummary)->setCv($cv);

    $result = $tool->handle(makeRequest(['summary' => 'New bullet-style summary.']));
    $op = $proposed->all()[0] ?? null;

    expect($result)->toContain('STAGED for review (NOT applied)')
        ->and($cv->refresh()->summary)->toBe('Old summary text.') // unchanged
        ->and($op)->not->toBeNull()
        ->and($op['after']['summary'])->toBe('New bullet-style summary.')
        ->and($op['before']['summary'])->toBe('Old summary text.');
});

it('rejects an empty summary update', function () {
    $cv = Cv::factory()->create(['summary' => 'Keep me.']);

    $tool = (new UpdateCvSummary)->setCv($cv);

    $result = $tool->handle(makeRequest(['summary' => '   ']));

    expect($result)->toContain('No summary provided')
        ->and($cv->refresh()->summary)->toBe('Keep me.');
});

it('refuses deletion when no cv is set', function () {
    $tool = new DeleteCvExperience;

    $result = $tool->handle(makeRequest(['id' => 1]));

    expect($result)->toContain('No CV found');
});

it('stores clarifying questions in the bridge and tells the agent to stop', function () {
    $pending = new PendingClarifications;
    $tool = new AskClarifyingQuestions($pending);

    $result = $tool->handle(makeRequest([
        'questions' => [
            [
                'question' => 'How many concurrent users did the app handle?',
                'why' => 'To quantify the scale of the project',
                'example' => 'e.g., around 1,000 peak users',
            ],
            [
                'question' => 'What was the response time improvement?',
            ],
        ],
    ]));

    // The model is told to stop and wait — it must not write in this turn.
    expect($result)->toContain('STOP')
        ->and($result)->toContain('shown to the user as an interactive form')
        // And the structured questions are handed to the bridge, each with a
        // stable id the Livewire UI can key answers off.
        ->and($pending->has())->toBeTrue()
        ->and($pending->get())->toHaveCount(2)
        ->and($pending->get()[0])->toMatchArray([
            'question' => 'How many concurrent users did the app handle?',
            'why' => 'To quantify the scale of the project',
            'example' => 'e.g., around 1,000 peak users',
        ])
        ->and($pending->get()[0]['id'])->toStartWith('q_');
});

it('rejects clarifying questions with an empty list', function () {
    $pending = new PendingClarifications;
    $tool = new AskClarifyingQuestions($pending);

    $result = $tool->handle(makeRequest(['questions' => []]));

    expect($result)->toContain('No questions provided')
        ->and($pending->has())->toBeFalse();
});

it('drops clarifying question entries missing the question text', function () {
    $pending = new PendingClarifications;
    $tool = new AskClarifyingQuestions($pending);

    $tool->handle(makeRequest([
        'questions' => [
            ['why' => 'some reason'], // no "question" field
            ['question' => '  '],     // blank
            ['question' => 'Valid question?'],
        ],
    ]));

    $stored = $pending->get();

    expect($stored)->toHaveCount(1)
        ->and($stored[0]['question'])->toBe('Valid question?');
});
