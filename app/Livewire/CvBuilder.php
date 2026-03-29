<?php

namespace App\Livewire;

use App\Models\Cv;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('CV Builder')]
class CvBuilder extends Component
{
    public ?Cv $cv = null;

    /** 'onboarding' | 'builder' */
    public string $stage = 'builder';

    public string $activeSection = 'personal';

    public array $templates = [];

    // Personal Info Form
    public array $personalInfo = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'location' => '',
        'linkedin' => '',
        'website' => '',
        'github' => '',
    ];

    public string $summary = '';

    public string $title = '';

    public string $selectedTemplate = 'professional-classic';

    // Form states
    public bool $showAiChat = false;

    public bool $showPreview = true;

    public function mount(?Cv $cv = null): void
    {
        if ($cv && $cv->exists) {
            $this->authorize('update', $cv);
            $this->cv = $cv;
            $this->stage = 'builder';
            $this->loadCvData();
        } else {
            $this->cv = new Cv;
            $this->personalInfo['email'] = Auth::user()->email;

            // Show onboarding when the user has no CVs yet
            $hasCvs = Auth::user()->cvs()->exists();
            $this->stage = $hasCvs ? 'builder' : 'onboarding';
        }

        $this->templates = $this->getAvailableTemplates();
    }

    /**
     * Select a template during onboarding and advance to the builder stage.
     */
    public function onboardingSelectTemplate(string $templateId): void
    {
        $this->selectedTemplate = $templateId;
        $this->stage = 'builder';
    }

    /**
     * Skip onboarding and go directly to the builder stage.
     */
    public function skipOnboarding(): void
    {
        $this->stage = 'builder';
    }

    #[On('cv-updated')]
    public function onCvUpdated(int $cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadCvData();
        }
    }

    public function loadCvData(): void
    {
        $this->title = $this->cv->title;
        $this->selectedTemplate = $this->cv->template_id;
        $this->summary = $this->cv->summary ?? '';
        $this->personalInfo = array_merge($this->personalInfo, $this->cv->personal_info ?? []);
    }

    public function getAvailableTemplates(): array
    {
        return [
            'professional-classic' => [
                'name' => 'Professional Classic',
                'description' => 'Traditional, corporate-friendly layout',
                'icon' => 'document-text',
            ],
            'technical-ats' => [
                'name' => 'Technical ATS',
                'description' => 'Optimized for Applicant Tracking Systems',
                'icon' => 'code-bracket',
            ],
            'modern-minimal' => [
                'name' => 'Modern Minimal',
                'description' => 'Clean, contemporary design',
                'icon' => 'sparkles',
            ],
            'creative' => [
                'name' => 'Creative',
                'description' => 'Visual sidebar with skill highlights',
                'icon' => 'paint-brush',
            ],
            'executive' => [
                'name' => 'Executive',
                'description' => 'Leadership-focused layout',
                'icon' => 'briefcase',
            ],
        ];
    }

    public function savePersonalInfo()
    {
        $this->validate([
            'personalInfo.first_name' => 'required|string|max:255',
            'personalInfo.last_name' => 'required|string|max:255',
            'personalInfo.email' => 'required|email|max:255',
            'personalInfo.phone' => 'nullable|string|max:50',
            'personalInfo.location' => 'nullable|string|max:255',
            'personalInfo.linkedin' => 'nullable|url|max:255',
            'personalInfo.website' => 'nullable|url|max:255',
            'personalInfo.github' => 'nullable|url|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:2000',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $this->title,
            'template_id' => $this->selectedTemplate,
            'personal_info' => $this->personalInfo,
            'summary' => $this->summary,
            'status' => 'draft',
        ];

        if ($this->cv->exists) {
            $this->cv->update($data);
            session()->flash('message', 'Personal information saved successfully!');

            return;
        }

        $this->cv = Cv::create($data);
        session()->flash('message', 'CV created successfully!');

        return redirect()->route('cv.edit', $this->cv);
    }

    public function updateTemplate(string $templateId): void
    {
        $this->selectedTemplate = $templateId;

        if ($this->cv->exists) {
            $this->cv->update(['template_id' => $templateId]);
        }

        $this->dispatch('template-changed', templateId: $templateId);
    }

    public function setActiveSection(string $section): void
    {
        $this->activeSection = $section;
    }

    public function toggleAiChat(): void
    {
        $this->showAiChat = ! $this->showAiChat;
    }

    public function togglePreview(): void
    {
        $this->showPreview = ! $this->showPreview;
    }

    public function getCvDataProperty(): array
    {
        if (! $this->cv->exists) {
            return [
                'personal_info' => $this->personalInfo,
                'summary' => $this->summary,
                'template_id' => $this->selectedTemplate,
            ];
        }

        return [
            'id' => $this->cv->id,
            'title' => $this->cv->title,
            'personal_info' => $this->cv->personal_info,
            'summary' => $this->cv->summary,
            'template_id' => $this->cv->template_id,
            'experiences' => $this->cv->experiences,
            'educations' => $this->cv->educations,
            'skills' => $this->cv->skills,
            'certifications' => $this->cv->certifications,
            'projects' => $this->cv->projects,
        ];
    }

    public function getSampleCvProperty(): object
    {
        $experiences = collect([
            (object) [
                'title' => 'Senior Product Designer',
                'company' => 'TechCorp Inc.',
                'location' => 'San Francisco, CA',
                'duration' => '2022 – Present',
                'description' => 'Leading a cross-functional design team in creating user-centered digital products for enterprise clients.',
                'achievements' => ['Increased user engagement by 40%', 'Established company-wide design system'],
                'technologies' => ['Figma', 'React', 'Tailwind CSS'],
                'start_date' => now()->subYears(3),
                'end_date' => null,
                'is_current' => true,
            ],
            (object) [
                'title' => 'Product Designer',
                'company' => 'StartupXYZ',
                'location' => 'New York, NY',
                'duration' => '2019 – 2022',
                'description' => 'Designed end-to-end user experiences for a B2B SaaS platform serving 50K+ users.',
                'achievements' => ['Shipped 3 major product launches'],
                'technologies' => ['Sketch', 'InVision', 'CSS'],
                'start_date' => now()->subYears(6),
                'end_date' => now()->subYears(3),
                'is_current' => false,
            ],
        ]);

        $educations = collect([
            (object) [
                'institution' => 'Stanford University',
                'degree' => 'Bachelor of Arts',
                'field_of_study' => 'Human-Computer Interaction',
                'start_date' => now()->subYears(10),
                'end_date' => now()->subYears(6),
                'is_current' => false,
            ],
        ]);

        $skills = collect([
            (object) ['name' => 'UI/UX Design', 'proficiency' => 5],
            (object) ['name' => 'Figma', 'proficiency' => 5],
            (object) ['name' => 'Design Systems', 'proficiency' => 4],
            (object) ['name' => 'Prototyping', 'proficiency' => 4],
            (object) ['name' => 'User Research', 'proficiency' => 4],
            (object) ['name' => 'React', 'proficiency' => 3],
            (object) ['name' => 'Tailwind CSS', 'proficiency' => 3],
            (object) ['name' => 'Accessibility', 'proficiency' => 4],
        ]);

        $certifications = collect([
            (object) [
                'name' => 'Google UX Design Certificate',
                'issuing_organization' => 'Google',
                'issue_date' => now()->subYears(4),
            ],
            (object) [
                'name' => 'NN/g UX Certification',
                'issuing_organization' => 'Nielsen Norman Group',
                'issue_date' => now()->subYear(),
            ],
        ]);

        return (object) [
            'personal_info' => [
                'first_name' => 'Sarah',
                'last_name' => 'Mitchell',
                'email' => 'sarah.mitchell@email.com',
                'phone' => '+1 (555) 234-5678',
                'location' => 'San Francisco, CA',
                'linkedin' => 'linkedin.com/in/sarahmitchell',
                'github' => 'github.com/sarahm',
                'website' => 'sarahmitchell.design',
                'title' => 'Senior Product Designer',
            ],
            'summary' => 'Senior product designer with 6+ years of experience crafting intuitive digital experiences for leading tech companies. Passionate about design systems and accessibility.',
            'title' => 'Senior Product Designer',
            'experiences' => $experiences,
            'educations' => $educations,
            'skills' => $skills,
            'certifications' => $certifications,
        ];
    }

    public function render()
    {
        return view('livewire.cv-builder');
    }
}
