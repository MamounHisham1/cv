<?php

use App\Models\Cv;
use App\Models\CvExperience;
use App\Services\ApplyCvChanges;
use App\Services\ProposedCvChanges;
use App\Support\CvFieldDiffFormatter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores proposed create/update/delete ops with stable ids and clears them', function () {
    $proposed = new ProposedCvChanges;

    $proposed->proposeCreate('skills', ['name' => 'Docker', 'sort_order' => 1], 'Docker', 'Add skill.');
    $proposed->proposeUpdate('experiences', 5, ['title' => 'Dev'], ['title' => 'Senior Dev'], 'Dev at Acme', 'Update title.');
    $proposed->proposeDelete('languages', 9, ['language' => 'French'], 'French', 'Delete language.');

    expect($proposed->has())->toBeTrue()
        ->and($proposed->count())->toBe(3)
        ->and($proposed->all()[0]['id'])->toBe('pc_1')
        ->and($proposed->all()[1]['id'])->toBe('pc_2')
        ->and($proposed->all()[2]['id'])->toBe('pc_3')
        ->and($proposed->all()[0]['action'])->toBe('create')
        ->and($proposed->all()[1]['action'])->toBe('update')
        ->and($proposed->all()[2]['action'])->toBe('delete');

    $proposed->clear();

    expect($proposed->has())->toBeFalse()
        ->and($proposed->count())->toBe(0);
});

it('merges successive update calls for the same record instead of duplicating', function () {
    // The LLM often calls update_cv_project twice for one project (once for
    // description, once for achievements). These must collapse to ONE op.
    $proposed = new ProposedCvChanges;

    $firstId = $proposed->proposeUpdate(
        section: 'projects',
        recordId: 7,
        before: ['name' => 'Seraty'],
        after: ['description' => 'New description.'],
        label: 'Seraty',
        summary: 'Update description.',
    );
    $secondId = $proposed->proposeUpdate(
        section: 'projects',
        recordId: 7,
        before: ['name' => 'Seraty'],
        after: ['key_achievements' => ['Built tool-calling AI']],
        label: 'Seraty',
        summary: 'Update description, achievements.',
    );

    expect($proposed->count())->toBe(1)               // not 2
        ->and($secondId)->toBe($firstId)              // same op id reused
        ->and($proposed->all()[0]['after'])->toBe([
            'description' => 'New description.',
            'key_achievements' => ['Built tool-calling AI'],
        ])
        ->and($proposed->all()[0]['summary'])->toBe('Update description, achievements.');
});

it('keeps separate ops for different records and different actions', function () {
    $proposed = new ProposedCvChanges;

    $proposed->proposeUpdate('projects', 7, [], ['description' => 'a'], 'P7', 'x');
    $proposed->proposeUpdate('projects', 8, [], ['description' => 'b'], 'P8', 'x'); // different record
    $proposed->proposeDelete('projects', 7, [], 'P7', 'x');                          // different action, same record

    expect($proposed->count())->toBe(3);
});

it('collapses identical Cv-field updates (personal_info / summary)', function () {
    // The model calling update_cv_personal_info twice with the same website
    // must not produce two identical "Update personal info" rows.
    $proposed = new ProposedCvChanges;

    $proposed->proposeCvField('personal_info', [], ['website' => 'https://x.test'], 'Personal information', 'Update website.');
    $proposed->proposeCvField('personal_info', [], ['website' => 'https://x.test'], 'Personal information', 'Update website.');

    expect($proposed->count())->toBe(1);
});

it('applies only approved ops in one transaction and resequences', function () {
    $cv = Cv::factory()->create();
    $keep = CvExperience::factory()->for($cv)->create(['title' => 'Keep', 'company' => 'A', 'sort_order' => 0]);
    $delete = CvExperience::factory()->for($cv)->create(['title' => 'Delete', 'company' => 'B', 'sort_order' => 1]);
    $update = CvExperience::factory()->for($cv)->create(['title' => 'Old', 'company' => 'C', 'sort_order' => 2]);

    $ops = [
        [ // approved: delete
            'id' => 'pc_1', 'action' => 'delete', 'section' => 'experiences',
            'record_id' => $delete->id, 'before' => $delete->toArray(), 'after' => [],
            'label' => 'Delete', 'summary' => 'x',
        ],
        [ // approved: update title
            'id' => 'pc_2', 'action' => 'update', 'section' => 'experiences',
            'record_id' => $update->id, 'before' => $update->toArray(), 'after' => ['title' => 'New'],
            'label' => 'Update', 'summary' => 'x',
        ],
        [ // approved: create a new one (all NOT NULL fields provided)
            'id' => 'pc_3', 'action' => 'create', 'section' => 'experiences',
            'record_id' => null, 'before' => [], 'after' => [
                'title' => 'Fresh', 'company' => 'D', 'start_date' => '2024-01-01',
                'description' => '', 'sort_order' => 99,
            ],
            'label' => 'Create', 'summary' => 'x',
        ],
    ];

    $result = app(ApplyCvChanges::class)->apply($cv, $ops);

    // Delete happened, update happened, create happened, resequence ran.
    expect($result['applied'])->toBe(3)
        ->and(CvExperience::find($delete->id))->toBeNull()
        ->and($update->refresh()->title)->toBe('New')
        ->and($cv->experiences()->count())->toBe(3)
        ->and(CvExperience::where('title', 'Fresh')->exists())->toBeTrue();

    // Resequence produced a contiguous 0..n sort_order across the section.
    $orders = $cv->experiences()->orderBy('id')->pluck('sort_order')->all();
    expect($orders)->toBe([0, 1, 2]);
});

it('applies nothing when given an empty op list', function () {
    $cv = Cv::factory()->create();
    CvExperience::factory()->for($cv)->create();

    $result = app(ApplyCvChanges::class)->apply($cv, []);

    expect($result['applied'])->toBe(0)
        ->and($cv->experiences()->count())->toBe(1);
});

it('formats date fields as M Y and arrays as comma lists in the diff', function () {
    $formatter = new CvFieldDiffFormatter;

    $rows = $formatter->rows([
        'action' => 'update',
        'section' => 'educations',
        'before' => ['end_date' => '2026-06-01', 'degree' => 'Old'],
        'after' => ['end_date' => '2024-06-01', 'degree' => 'New'],
    ]);

    $byField = collect($rows)->keyBy('field');

    expect($byField['End date']['before'])->toBe('Jun 2026')
        ->and($byField['End date']['after'])->toBe('Jun 2024')
        ->and($byField['Degree']['before'])->toBe('Old')
        ->and($byField['Degree']['after'])->toBe('New');
});

it('omits unchanged fields from the diff rows', function () {
    $formatter = new CvFieldDiffFormatter;

    $rows = $formatter->rows([
        'action' => 'update',
        'section' => 'experiences',
        'before' => ['title' => 'Dev', 'company' => 'Acme'],
        'after' => ['title' => 'Dev', 'company' => 'Acme Corp'],
    ]);

    // 'title' unchanged — only 'company' should appear.
    expect($rows)->toHaveCount(1)
        ->and($rows[0]['field'])->toBe('Company');
});

it('renders every field of a create op with no before value', function () {
    $formatter = new CvFieldDiffFormatter;

    $rows = $formatter->rows([
        'action' => 'create',
        'section' => 'skills',
        'before' => [],
        'after' => ['name' => 'Docker', 'category' => 'technical', 'level' => 'advanced'],
    ]);

    $byField = collect($rows)->keyBy('field');

    expect($byField)->toHaveCount(3)
        ->and($byField['Name']['after'])->toBe('Docker')
        ->and($byField['Name']['before'])->toBe('')
        ->and($byField['Category']['after'])->toBe('technical');
});
