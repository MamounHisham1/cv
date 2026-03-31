<?php

namespace App\Livewire;

use App\Ai\Agents\CvParser;
use App\Models\Cv;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvSkill;
use App\Services\CreditManager;
use App\Services\CvTextExtractor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class CvImporter extends Component
{
    use WithFileUploads;

    public string $step = 'choose';

    public string $selectedTemplate = 'professional-classic';

    public $uploadedFile = null;

    public bool $isProcessing = false;

    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (Auth::user()->cvs()->exists()) {
            $this->step = 'choose';
        }
    }

    public function selectTemplate(string $templateId): void
    {
        $this->selectedTemplate = $templateId;
    }

    public function startFromScratch(): void
    {
        $cv = Cv::create([
            'user_id' => Auth::id(),
            'title' => 'My CV',
            'template_id' => $this->selectedTemplate,
            'personal_info' => [
                'first_name' => '',
                'last_name' => '',
                'email' => Auth::user()->email,
                'phone' => '',
                'location' => '',
                'linkedin' => '',
                'github' => '',
                'website' => '',
            ],
            'status' => 'draft',
        ]);

        $this->redirect(route('cv.edit', $cv));
    }

    public function showUpload(): void
    {
        $this->step = 'upload';
        $this->errorMessage = null;
    }

    public function showChoose(): void
    {
        $this->step = 'choose';
        $this->uploadedFile = null;
        $this->errorMessage = null;
    }

    public function importCv(): void
    {
        $this->validate([
            'uploadedFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $this->isProcessing = true;
        $this->errorMessage = null;

        try {
            $creditManager = app(CreditManager::class);
            if (! $creditManager->hasCredits(Auth::user())) {
                $this->errorMessage = "You're out of credits. Invite friends to earn more!";
                $this->isProcessing = false;
                $this->dispatch('insufficient-credits');

                return;
            }

            $extractor = new CvTextExtractor;
            $text = $extractor->extract($this->uploadedFile);

            if (empty(trim($text))) {
                $this->errorMessage = 'Could not extract text from the file. Try pasting your CV content directly.';

                $this->isProcessing = false;

                return;
            }

            $agent = new CvParser;
            $response = $agent->prompt("Extract all data from this CV into the exact schema fields (first_name, last_name, email, phone, location, linkedin, github, website, title, summary, experiences, skills, educations, certifications). Return ONLY valid JSON with these exact keys:\n\n{$text}");

            $data = $response->structured;

            $personalInfo = [
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'email' => $data['email'] ?? Auth::user()->email,
                'phone' => $data['phone'] ?? '',
                'location' => $data['location'] ?? '',
                'linkedin' => $data['linkedin'] ?? '',
                'github' => $data['github'] ?? '',
                'website' => $data['website'] ?? '',
            ];

            $title = $data['title'] ?? 'Imported CV';
            $summary = $data['summary'] ?? '';

            $cv = Cv::create([
                'user_id' => Auth::id(),
                'title' => $title,
                'template_id' => $this->selectedTemplate,
                'personal_info' => $personalInfo,
                'summary' => $summary,
                'status' => 'draft',
            ]);

            $this->importExperiences($cv, $data['experiences'] ?? '[]');
            $this->importSkills($cv, $data['skills'] ?? '[]');
            $this->importEducations($cv, $data['educations'] ?? '[]');
            $this->importCertifications($cv, $data['certifications'] ?? '[]');

            $credits = $creditManager->calculateFromUsage($response->usage, 'ai_parse');
            $creditManager->deduct(Auth::user(), $credits, 'ai_parse', $cv, [
                'prompt_tokens' => $response->usage->promptTokens,
                'completion_tokens' => $response->usage->completionTokens,
            ]);
            $this->dispatch('credits-updated');

            $this->redirect(route('cv.edit', $cv));
        } catch (\Throwable $e) {
            Log::error('CvImporter: Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->errorMessage = 'Failed to process your CV. Please try again or start from scratch.';
            $this->isProcessing = false;
        }
    }

    private function importExperiences(Cv $cv, string $json): void
    {
        $experiences = json_decode($json, true);

        if (! is_array($experiences)) {
            return;
        }

        foreach ($experiences as $index => $exp) {
            CvExperience::create([
                'cv_id' => $cv->id,
                'company' => $exp['company'] ?? '',
                'title' => $exp['title'] ?? '',
                'location' => $exp['location'] ?? '',
                'start_date' => $exp['start_date'] ?? null,
                'end_date' => $exp['end_date'] ?? null,
                'is_current' => $exp['is_current'] ?? false,
                'description' => $exp['description'] ?? '',
                'achievements' => $exp['achievements'] ?? [],
                'sort_order' => $index,
            ]);
        }
    }

    private function importSkills(Cv $cv, string $json): void
    {
        $skills = json_decode($json, true);

        if (! is_array($skills)) {
            return;
        }

        foreach ($skills as $index => $skill) {
            CvSkill::create([
                'cv_id' => $cv->id,
                'name' => $skill['name'] ?? '',
                'category' => $skill['category'] ?? 'general',
                'level' => $skill['level'] ?? 'intermediate',
                'sort_order' => $index,
            ]);
        }
    }

    private function importEducations(Cv $cv, string $json): void
    {
        $educations = json_decode($json, true);

        if (! is_array($educations)) {
            return;
        }

        foreach ($educations as $index => $edu) {
            CvEducation::create([
                'cv_id' => $cv->id,
                'institution' => $edu['institution'] ?? '',
                'degree' => $edu['degree'] ?? '',
                'field_of_study' => $edu['field_of_study'] ?? '',
                'location' => $edu['location'] ?? '',
                'start_date' => $edu['start_date'] ?? null,
                'end_date' => $edu['end_date'] ?? null,
                'is_current' => $edu['is_current'] ?? false,
                'sort_order' => $index,
            ]);
        }
    }

    private function importCertifications(Cv $cv, string $json): void
    {
        $certifications = json_decode($json, true);

        if (! is_array($certifications)) {
            return;
        }

        foreach ($certifications as $index => $cert) {
            CvCertification::create([
                'cv_id' => $cv->id,
                'name' => $cert['name'] ?? '',
                'issuing_organization' => $cert['issuing_organization'] ?? '',
                'issue_date' => $cert['issue_date'] ?? null,
                'expiration_date' => $cert['expiration_date'] ?? null,
                'credential_id' => $cert['credential_id'] ?? '',
                'sort_order' => $index,
            ]);
        }
    }

    #[Computed]
    public function templates(): array
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

    public function render()
    {
        return view('livewire.cv-importer');
    }
}
