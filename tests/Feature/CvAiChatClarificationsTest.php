<?php

use App\Livewire\CvAiChat;
use App\Models\Cv;
use App\Models\CvExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the wizard showing only the first clarifying question', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->set('pendingClarifications', [
            [
                'id' => 'q_1',
                'question' => 'How many concurrent users did the app handle?',
                'why' => 'To quantify the scale of the project',
                'example' => 'e.g., around 1,000 peak users',
            ],
            [
                'id' => 'q_2',
                'question' => 'What was the response time improvement?',
                'why' => '',
                'example' => '',
            ],
        ])
        // Only the first question is shown — the wizard shows one at a time.
        ->assertSee('Quick questions')
        ->assertSee('1 / 2')
        ->assertSee('How many concurrent users did the app handle?')
        ->assertSee('To quantify the scale of the project')
        // The second question is NOT shown until the user advances.
        ->assertDontSee('What was the response time improvement?')
        ->assertSee('Next')
        ->assertSee('Skip all');
});

it('advances the wizard forward and back through questions', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();

    $component = Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->set('pendingClarifications', [
            ['id' => 'q_1', 'question' => 'First?', 'why' => '', 'example' => ''],
            ['id' => 'q_2', 'question' => 'Second?', 'why' => '', 'example' => ''],
        ]);

    // Back is disabled on the first question, Next advances to the second.
    $component
        ->assertSee('First?')
        ->assertDontSee('Second?')
        ->call('nextClarification')
        ->assertSet('currentClarificationIndex', 1)
        ->assertSee('Second?')
        ->assertSee('Finish') // last question shows "Finish" instead of "Next"
        ->call('previousClarification')
        ->assertSet('currentClarificationIndex', 0)
        ->assertSee('First?');
});

it('does not render the clarifications panel when none are pending', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->assertDontSee('Quick questions from the assistant')
        ->assertDontSee('Submit answers');
});

it('hides the regular chat input while clarifications are pending', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();

    $html = Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->set('pendingClarifications', [
            ['id' => 'q_1', 'question' => 'How many users?', 'why' => '', 'example' => ''],
        ])
        ->html();

    // The chat form carries x-show="!$wire.pendingClarifications" so the
    // input stays in the DOM (toggleable) but is hidden while questions pend.
    expect($html)->toContain('x-show="!$wire.pendingClarifications');
});

it('renders the proposed-changes review card when changes are staged', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->set('proposedChanges', [
            [
                'id' => 'pc_1',
                'action' => 'update',
                'section' => 'educations',
                'record_id' => 7,
                'before' => ['end_date' => '2026-06-01'],
                'after' => ['end_date' => '2024-06-01'],
                'label' => 'Bachelor at MIT',
                'summary' => 'Update education.',
            ],
        ])
        ->assertSee('proposed change')
        ->assertSee('review before applying')
        ->assertSee('Bachelor at MIT')
        ->assertSee('Apply changes')
        ->assertSee('Reject all')
        ->assertSee('End date')
        ->assertSee('Jun 2026')
        ->assertSee('Jun 2024');
});

it('hides the chat input while proposed changes are pending', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();

    $html = Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->set('proposedChanges', [
            ['id' => 'pc_1', 'action' => 'update', 'section' => 'summary', 'record_id' => null, 'before' => [], 'after' => ['summary' => 'x'], 'label' => 'Summary', 'summary' => 'x'],
        ])
        ->html();

    // The form is hidden (not removed) while changes are pending review.
    expect($html)->toContain('x-show="!$wire.pendingClarifications && !$wire.proposedChanges"');
});

it('toggles and approves only kept proposed changes', function () {
    $user = User::factory()->create();
    $cv = Cv::factory()->for($user)->create();
    $exp = CvExperience::factory()->for($cv)->create(['title' => 'Old', 'company' => 'A']);

    $component = Livewire::actingAs($user)
        ->test(CvAiChat::class, ['cv' => $cv])
        ->set('proposedChanges', [
            ['id' => 'pc_1', 'action' => 'update', 'section' => 'experiences', 'record_id' => $exp->id, 'before' => ['title' => 'Old'], 'after' => ['title' => 'New'], 'label' => 'Old', 'summary' => 'x'],
        ]);

    // Deselect the change, then approve — nothing should be applied.
    $component->call('toggleChange', 'pc_1')
        ->assertSet('rejectedChangeIds', ['pc_1'])
        ->call('approveChanges');

    expect($exp->refresh()->title)->toBe('Old'); // untouched
});
