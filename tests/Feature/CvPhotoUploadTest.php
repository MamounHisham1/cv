<?php

use App\Livewire\CvBuilder;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('stores an uploaded profile photo into personal_info and renders in photo templates', function () {
    Storage::fake('public');

    $cv = Cv::factory()->for($this->user)->create([
        'template_id' => 'creative',
        'personal_info' => ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com'],
    ]);

    $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

    Livewire::actingAs($this->user)
        ->test(CvBuilder::class, ['cv' => $cv])
        ->set('photoUpload', $file)
        ->call('savePhoto')
        ->assertDispatched('cv-updated');

    $cv->refresh();

    expect($cv->personal_info)->toHaveKey('photo')
        ->and($cv->personal_info['photo'])->toStartWith('/storage/cv-photos/cv-'.$cv->id.'/')
        ->and(Storage::disk('public')->files('cv-photos/cv-'.$cv->id))->not->toBeEmpty();

    // Photo renders inside the creative template.
    $rendered = view('cv.templates.creative', ['cv' => $cv])->render();
    expect($rendered)->toContain('<img')
        ->and($rendered)->toContain($cv->personal_info['photo']);
});

it('clears the photo and falls back to initials when removed', function () {
    Storage::fake('public');

    $cv = Cv::factory()->for($this->user)->create([
        'template_id' => 'warm',
        'personal_info' => [
            'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com',
            'photo' => '/storage/cv-photos/cv-1/old.jpg',
        ],
    ]);

    // The stored path is fake; removal should still clear the key gracefully.
    Livewire::actingAs($this->user)
        ->test(CvBuilder::class, ['cv' => $cv])
        ->call('removePhoto')
        ->assertDispatched('cv-updated');

    $cv->refresh();

    expect($cv->personal_info)->not->toHaveKey('photo');

    // Warm template now shows the initials monogram, not an <img>.
    $rendered = view('cv.templates.warm', ['cv' => $cv])->render();
    expect($rendered)->not->toContain('<img')
        ->and($rendered)->toContain('J')->and($rendered)->toContain('D');
});

it('rejects non-image uploads', function () {
    Storage::fake('public');

    $cv = Cv::factory()->for($this->user)->create(['template_id' => 'creative']);

    $bad = UploadedFile::fake()->create('not-an-image.pdf', 100, 'application/pdf');

    Livewire::actingAs($this->user)
        ->test(CvBuilder::class, ['cv' => $cv])
        ->set('photoUpload', $bad)
        ->call('savePhoto')
        ->assertHasErrors(['photoUpload']);
});
