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
            $this->loadCvData();
        } else {
            $this->cv = new Cv;
            $this->personalInfo['email'] = Auth::user()->email;
        }

        $this->templates = $this->getAvailableTemplates();
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

    public function render()
    {
        return view('livewire.cv-builder');
    }
}
