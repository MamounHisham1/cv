<?php

use App\Models\Cv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('CV Builder', function () {
    it('redirects unauthenticated users to login', function () {
        $response = $this->get('/cv-builder');

        $response->assertRedirect('/login');
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
