<?php

use App\Livewire\CvAiChat;
use App\Livewire\CvCertificationsManager;
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
            ->assertSee('border-white/10', false);

        Livewire::actingAs($this->user)
            ->test(CvCertificationsManager::class, ['cv' => $cv])
            ->call('addCertification')
            ->assertSee('Certifications')
            ->assertSee('bg-zinc-950/80', false)
            ->assertSee('form-field', false)
            ->assertSee('border-white/10', false);

        Livewire::actingAs($this->user)
            ->test(CvAiChat::class, ['cv' => $cv])
            ->assertSee('AI Assistant')
            ->assertSee('bg-white/5', false)
            ->assertSee('backdrop-blur-sm', false);
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
