<?php

use App\Livewire\CvExperienceManager;
use App\Livewire\CvSkillsManager;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('CV Builder', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->get('/cv-builder');

        $response->assertRedirect('/login');
    });

    it('renders the builder with design 4 glassmorphism styling', function () {
        Cv::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->get(route('cv.builder'))
            ->assertOk()
            ->assertSee('Design 4 Builder')
            ->assertSee('bg-zinc-950/80', false)
            ->assertSee('backdrop-blur-xl', false)
            ->assertSee('bg-zinc-900/50', false)
            ->assertSee('border-white/10', false)
            ->assertSee('form-field', false)
            ->assertSee('focus-visible:border-emerald-500/50', false);
    });

    it('has all 5 templates available', function () {
        $templates = [
            'professional-classic',
            'technical-ats',
            'modern-minimal',
            'creative',
            'executive',
        ];

        foreach ($templates as $template) {
            $viewPath = resource_path("views/cv/templates/{$template}.blade.php");
            expect(file_exists($viewPath))->toBeTrue("Template {$template} should exist");
        }
    });

    it('can create a CV in database', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'title' => 'My Test CV',
                'template_id' => 'professional-classic',
                'personal_info' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                ],
            ]);

        $this->assertDatabaseHas('cvs', [
            'id' => $cv->id,
            'title' => 'My Test CV',
        ]);
    });

    it('renders the manager and ai chat components with the shared glass styling', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'title' => 'Design 4 CV',
                'template_id' => 'professional-classic',
                'personal_info' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                ],
            ]);

        Livewire::actingAs($this->user)
            ->test(CvExperienceManager::class, ['cv' => $cv])
            ->call('addExperience')
            ->assertSee('Work Experience')
            ->assertSee('bg-zinc-950/80', false)
            ->assertSee('form-section', false)
            ->assertSee('form-field', false)
            ->assertSee('border-white/10', false);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->call('addSkill')
            ->assertSee('Quick Add Common Skills')
            ->assertSee('bg-zinc-950/80', false)
            ->assertSee('form-field', false)
            ->assertSee('border-white/10', false)
            ->assertSee('Type to search or create new...');
    });

    it('allows adding a skill with a custom category', function () {
        $cv = Cv::factory()->for($this->user)->create([
            'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->set('form.name', 'Docker')
            ->set('form.category', 'Cloud & DevOps')
            ->set('form.level', 'advanced')
            ->call('saveSkill')
            ->assertDispatched('notify', message: 'Skill added successfully!')
            ->assertSet('showForm', false);

        expect($cv->refresh()->skills)->toHaveCount(1);
        expect($cv->skills->first()->category)->toBe('cloud & devops');
        expect($cv->skills->first()->name)->toBe('Docker');
    });

    it('persists custom categories per user', function () {
        $cv = Cv::factory()->for($this->user)->create([
            'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->set('form.name', 'Docker')
            ->set('form.category', 'Cloud & DevOps')
            ->call('saveSkill');

        expect($this->user->refresh()->skillCategories)->toHaveCount(1);
        expect($this->user->skillCategories->first()->name)->toBe('Cloud & devops');

        $otherUser = User::factory()->create();
        Livewire::actingAs($otherUser)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->assertSet('categories', function ($categories) {
                return ! in_array('Cloud & devops', $categories, true);
            });
    });

    it('loads saved custom categories on mount', function () {
        $cv = Cv::factory()->for($this->user)->create([
            'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
        ]);

        $this->user->skillCategories()->create(['name' => 'My Custom Cat']);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->assertSet('categories', function ($categories) {
                return in_array('My Custom Cat', $categories, true);
            });
    });

    it('allows adding a skill with a preset category', function () {
        $cv = Cv::factory()->for($this->user)->create([
            'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->set('form.name', 'Python')
            ->set('form.category', 'General')
            ->set('form.level', 'advanced')
            ->call('saveSkill')
            ->assertDispatched('notify', message: 'Skill added successfully!')
            ->assertSet('showForm', false);

        expect($cv->refresh()->skills)->toHaveCount(1);
        expect($cv->skills->first()->category)->toBe('general');
        expect($this->user->refresh()->skillCategories)->toHaveCount(0);
    });

    it('shows category search input in the form', function () {
        $cv = Cv::factory()->for($this->user)->create([
            'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->call('addSkill')
            ->assertSee('Type to search or create new...');
    });

    it('has empty category by default when adding a skill', function () {
        $cv = Cv::factory()->for($this->user)->create([
            'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
        ]);

        Livewire::actingAs($this->user)
            ->test(CvSkillsManager::class, ['cv' => $cv])
            ->call('addSkill')
            ->assertSet('form.category', '');
    });
});

describe('CV Templates', function () {
    it('renders professional-classic template without errors', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'template_id' => 'professional-classic',
                'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
            ]);
        $cv->setRelation('educations', collect());
        $cv->setRelation('experiences', collect());
        $cv->setRelation('skills', collect());
        $cv->setRelation('certifications', collect());

        $view = view('cv.templates.professional-classic', ['cv' => $cv]);

        expect($view->render())->toBeString();
    });

    it('renders technical-ats template without errors', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'template_id' => 'technical-ats',
                'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
            ]);
        $cv->setRelation('educations', collect());
        $cv->setRelation('experiences', collect());
        $cv->setRelation('skills', collect());
        $cv->setRelation('certifications', collect());

        $view = view('cv.templates.technical-ats', ['cv' => $cv]);

        expect($view->render())->toBeString();
    });

    it('renders modern-minimal template without errors', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'template_id' => 'modern-minimal',
                'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
            ]);
        $cv->setRelation('educations', collect());
        $cv->setRelation('experiences', collect());
        $cv->setRelation('skills', collect());
        $cv->setRelation('certifications', collect());

        $view = view('cv.templates.modern-minimal', ['cv' => $cv]);

        expect($view->render())->toBeString();
    });

    it('renders creative template without errors', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'template_id' => 'creative',
                'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
            ]);
        $cv->setRelation('educations', collect());
        $cv->setRelation('experiences', collect());
        $cv->setRelation('skills', collect());
        $cv->setRelation('certifications', collect());

        $view = view('cv.templates.creative', ['cv' => $cv]);

        expect($view->render())->toBeString();
    });

    it('renders executive template without errors', function () {
        $cv = Cv::factory()
            ->for($this->user)
            ->create([
                'template_id' => 'executive',
                'personal_info' => ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
            ]);
        $cv->setRelation('educations', collect());
        $cv->setRelation('experiences', collect());
        $cv->setRelation('skills', collect());
        $cv->setRelation('certifications', collect());

        $view = view('cv.templates.executive', ['cv' => $cv]);

        expect($view->render())->toBeString();
    });
});
