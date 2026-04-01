<?php

namespace App\Livewire;

use App\Ai\Agents\CvParser;
use App\Jobs\ExtractCvText;
use App\Models\Cv;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvLanguage;
use App\Models\CvProject;
use App\Models\CvSkill;
use App\Services\CreditManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('CV Builder')]
class CvBuilder extends Component
{
    use WithFileUploads;

    public ?Cv $cv = null;

    /** 'onboarding' | 'builder' */
    public string $stage = 'builder';

    public string $activeSection = 'personal';

    public array $sections = [];

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

    // Onboarding modal state
    public bool $showOnboardingModal = false;

    public $uploadedFile = null;

    public bool $isImporting = false;

    public ?string $importError = null;

    /** 'idle' | 'extracting' | 'importing' */
    public string $importStage = 'idle';

    public ?string $extractionCacheKey = null;

    public ?string $tempFilePath = null;

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

            // Show onboarding when the user has no CVs yet or explicitly requested
            $hasCvs = Auth::user()->cvs()->exists();
            $this->stage = (! $hasCvs || request()->has('onboarding')) ? 'onboarding' : 'builder';
        }

        $this->templates = $this->getAvailableTemplates();
        $this->sections = $this->getSections();
    }

    public function getSections(): array
    {
        $default = [
            'personal' => ['name' => 'Personal', 'icon' => 'user'],
            'experience' => ['name' => 'Experience', 'icon' => 'briefcase'],
            'skills' => ['name' => 'Skills', 'icon' => 'zap'],
            'certifications' => ['name' => 'Certifications', 'icon' => 'trophy'],
            'education' => ['name' => 'Education', 'icon' => 'graduation-cap'],
            'projects' => ['name' => 'Projects', 'icon' => 'folder'],
            'languages' => ['name' => 'Languages', 'icon' => 'globe'],
        ];

        if ($this->cv && $this->cv->exists && $this->cv->section_order) {
            $ordered = [];
            foreach ($this->cv->section_order as $key) {
                if (isset($default[$key])) {
                    $ordered[$key] = $default[$key];
                }
            }

            foreach ($default as $key => $value) {
                if (! isset($ordered[$key])) {
                    $ordered[$key] = $value;
                }
            }

            return $ordered;
        }

        return $default;
    }

    /**
     * Select a template during onboarding and show the action modal.
     */
    public function onboardingSelectTemplate(string $templateId): void
    {
        $this->selectedTemplate = $templateId;
        $this->showOnboardingModal = true;
        $this->uploadedFile = null;
        $this->importError = null;
    }

    /**
     * Close the onboarding modal.
     */
    public function closeOnboardingModal(): void
    {
        $this->showOnboardingModal = false;
        $this->uploadedFile = null;
        $this->importError = null;
    }

    /**
     * Switch back to the onboarding stage to create a new CV.
     */
    public function goToOnboarding(): void
    {
        $this->cv = new Cv;
        $this->personalInfo = [
            'first_name' => '',
            'last_name' => '',
            'email' => Auth::user()->email,
            'phone' => '',
            'location' => '',
            'linkedin' => '',
            'github' => '',
            'website' => '',
        ];
        $this->title = '';
        $this->summary = '';
        $this->selectedTemplate = 'professional-classic';
        $this->activeSection = 'personal';
        $this->stage = 'onboarding';
    }

    /**
     * Create a blank CV with the selected template and switch to the builder.
     */
    public function createFromScratch(): void
    {
        $this->showOnboardingModal = false;

        $this->cv = Cv::create([
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

        $this->stage = 'builder';
        $this->activeSection = 'personal';
    }

    /**
     * Import an existing CV from an uploaded file.
     */
    public function importCv(): void
    {
        $this->validate([
            'uploadedFile' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $creditManager = app(CreditManager::class);
        if (! $creditManager->hasCredits(Auth::user())) {
            $this->importError = "You're out of credits. Invite friends to earn more!";
            $this->dispatch('insufficient-credits');

            return;
        }

        $this->importError = null;
        $this->isImporting = true;
        $this->importStage = 'extracting';

        $extension = strtolower($this->uploadedFile->getClientOriginalExtension());
        $this->tempFilePath = $this->uploadedFile->storeAs('temp/uploads', uniqid('cv_').'.'.$extension);
        $this->extractionCacheKey = 'cv_extract_'.uniqid();

        $fullPath = storage_path('app/'.$this->tempFilePath);

        ExtractCvText::dispatch($fullPath, $extension, $this->extractionCacheKey);
    }

    public function checkExtractionStatus(): void
    {
        if ($this->importStage !== 'extracting' || ! $this->extractionCacheKey) {
            return;
        }

        $status = Cache::get($this->extractionCacheKey.'_status');

        if ($status === 'completed') {
            $text = Cache::get($this->extractionCacheKey);

            if (empty(trim($text))) {
                $this->importError = 'Could not extract text from the file. Try a different format or start from scratch.';
                $this->resetImportState();
                $this->cleanupTempFile();

                return;
            }

            $this->importStage = 'importing';
            $this->processAiParsing($text);
        } elseif ($status === 'failed') {
            $this->importError = Cache::get($this->extractionCacheKey.'_error', 'Failed to extract text from the file.');
            $this->resetImportState();
            $this->cleanupTempFile();
        }
    }

    private function processAiParsing(string $text): void
    {
        try {
            $creditManager = app(CreditManager::class);

            $agent = new CvParser;
            $response = $agent->prompt("Extract all data from this CV into the exact schema fields (first_name, last_name, email, phone, location, linkedin, github, website, title, summary, experiences, skills, educations, certifications, projects, languages). Return ONLY valid JSON with these exact keys:\n\n{$text}");

            Log::info('CvBuilder: AI raw response', [
                'response_class' => get_class($response),
                'text' => substr($response->text, 0, 500),
                'has_structured' => isset($response->structured),
                'structured' => isset($response->structured) ? $response->structured : null,
            ]);

            $data = $response->structured;
            $data = $this->normalizeImportData($data);

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

            $cv = Cv::create([
                'user_id' => Auth::id(),
                'title' => $data['title'] ?? 'Imported CV',
                'template_id' => $this->selectedTemplate,
                'personal_info' => $personalInfo,
                'summary' => $data['summary'] ?? '',
                'status' => 'draft',
            ]);

            $this->importExperiences($cv, $data['experiences'] ?? []);
            $this->importSkills($cv, $data['skills'] ?? []);
            $this->importEducations($cv, $data['educations'] ?? []);
            $this->importCertifications($cv, $data['certifications'] ?? []);
            $this->importProjects($cv, $data['projects'] ?? []);
            $this->importLanguages($cv, $data['languages'] ?? []);

            Log::info('CvBuilder: Import completed', [
                'cv_id' => $cv->id,
                'title' => $cv->title,
                'template_id' => $cv->template_id,
                'personal_info' => $cv->personal_info,
                'summary' => $cv->summary,
                'experiences_count' => $cv->experiences->count(),
                'experiences' => $cv->experiences->map->only(['company', 'title', 'location', 'start_date', 'end_date', 'is_current'])->toArray(),
                'skills_count' => $cv->skills->count(),
                'skills' => $cv->skills->map->only(['name', 'category', 'level'])->toArray(),
                'educations_count' => $cv->educations->count(),
                'educations' => $cv->educations->map->only(['institution', 'degree', 'field_of_study', 'start_date', 'end_date'])->toArray(),
                'certifications_count' => $cv->certifications->count(),
                'certifications' => $cv->certifications->map->only(['name', 'issuing_organization', 'issue_date'])->toArray(),
                'projects_count' => $cv->projects->count(),
                'projects' => $cv->projects->map->only(['name', 'description', 'start_date', 'end_date'])->toArray(),
                'languages_count' => $cv->languages->count(),
                'languages' => $cv->languages->map->only(['language', 'proficiency'])->toArray(),
            ]);

            $this->cv = $cv;
            $this->loadCvData();
            $this->showOnboardingModal = false;
            $this->stage = 'builder';
            $this->activeSection = 'personal';
            $this->dispatch('cv-saved', cvId: $cv->id);
            $this->dispatch('cv-updated', cvId: $cv->id);

            $credits = $creditManager->calculateFromUsage($response->usage, 'ai_parse');
            $creditManager->deduct(Auth::user(), $credits, 'ai_parse', $cv, [
                'prompt_tokens' => $response->usage->promptTokens,
                'completion_tokens' => $response->usage->completionTokens,
            ]);
            $this->dispatch('credits-updated');

            $this->resetImportState();
            $this->cleanupTempFile();
        } catch (\Throwable $e) {
            Log::error('CvBuilder: Import failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->importError = 'Failed to process your CV. Please try again or start from scratch.';
            $this->resetImportState();
            $this->cleanupTempFile();
        }
    }

    private function resetImportState(): void
    {
        $this->isImporting = false;
        $this->importStage = 'idle';
        $this->extractionCacheKey = null;
    }

    private function cleanupTempFile(): void
    {
        if ($this->tempFilePath) {
            Storage::delete($this->tempFilePath);
            $this->tempFilePath = null;
        }
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
            'bold' => [
                'name' => 'Bold',
                'description' => 'Eye-catching header with vibrant indigo tones and categorized skills',
                'icon' => 'fire',
            ],
            'timeline' => [
                'name' => 'Timeline',
                'description' => 'Visual career timeline with connected dots and date axis',
                'icon' => 'clock',
            ],
            'swiss' => [
                'name' => 'Swiss',
                'description' => 'Grid-based typographic design with bold red accents',
                'icon' => 'grid',
            ],
            'warm' => [
                'name' => 'Warm',
                'description' => 'Approachable two-column layout with warm cream sidebar and amber accents',
                'icon' => 'sun',
            ],
            'compact' => [
                'name' => 'Compact',
                'description' => 'Dense single-column layout for experienced professionals',
                'icon' => 'arrows-pointing-in',
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

    public function updateSectionOrder(array $orderedKeys): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            return;
        }

        $this->cv->update(['section_order' => $orderedKeys]);
        $this->sections = $this->getSections();
    }

    public function handleSectionSort(string $sectionKey, int $position): void
    {
        $currentKeys = array_keys($this->sections);
        $currentKeys = array_values(array_diff($currentKeys, [$sectionKey]));
        array_splice($currentKeys, $position, 0, $sectionKey);
        $this->updateSectionOrder($currentKeys);
    }

    public function moveSectionUp(string $sectionKey): void
    {
        $keys = array_keys($this->sections);
        $index = array_search($sectionKey, $keys);

        if ($index === false || $index === 0) {
            return;
        }

        array_splice($keys, $index - 1, 0, array_splice($keys, $index, 1)[0]);
        $this->updateSectionOrder($keys);
    }

    public function moveSectionDown(string $sectionKey): void
    {
        $keys = array_keys($this->sections);
        $index = array_search($sectionKey, $keys);

        if ($index === false || $index === count($keys) - 1) {
            return;
        }

        array_splice($keys, $index + 1, 0, array_splice($keys, $index, 1)[0]);
        $this->updateSectionOrder($keys);
    }

    public function moveSectionToTop(string $sectionKey): void
    {
        $keys = array_keys($this->sections);
        $index = array_search($sectionKey, $keys);

        if ($index === false || $index === 0) {
            return;
        }

        array_splice($keys, $index, 1);
        array_unshift($keys, $sectionKey);
        $this->updateSectionOrder($keys);
    }

    public function moveSectionToBottom(string $sectionKey): void
    {
        $keys = array_keys($this->sections);
        $index = array_search($sectionKey, $keys);

        if ($index === false || $index === count($keys) - 1) {
            return;
        }

        array_splice($keys, $index, 1);
        $keys[] = $sectionKey;
        $this->updateSectionOrder($keys);
    }

    public function autosavePersonalInfo(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->savePersonalInfo();

            return;
        }

        try {
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

            $this->cv->update([
                'title' => $this->title,
                'template_id' => $this->selectedTemplate,
                'personal_info' => $this->personalInfo,
                'summary' => $this->summary,
            ]);
        } catch (ValidationException $e) {
        }
    }

    public function toggleAiChat(): void
    {
        $this->showAiChat = ! $this->showAiChat;
    }

    public function togglePreview(): void
    {
        $this->showPreview = ! $this->showPreview;
    }

    public function getSampleCvProperty(): Cv
    {
        return new class extends Cv
        {
            public $title = 'Sample CV';

            public $template_id = 'professional-classic';

            public $personal_info = [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '+1 (555) 123-4567',
                'location' => 'San Francisco, CA',
            ];

            public $summary = 'Experienced software developer with expertise in building scalable web applications.';

            public function __construct()
            {
                $this->exists = true;
                $this->relations = [
                    'experiences' => collect(),
                    'educations' => collect(),
                    'skills' => collect(),
                    'certifications' => collect(),
                    'projects' => collect(),
                ];
            }
        };
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

    private function normalizeImportData(array $data): array
    {
        // If the AI returned the expected flat structure, return as-is
        if (isset($data['first_name']) || isset($data['experiences'])) {
            return $data;
        }

        $normalized = [];

        // Handle nested personal_information object
        $pi = $data['personal_information'] ?? $data['personal_info'] ?? [];
        if (isset($pi['name']) && ! isset($pi['first_name'])) {
            $nameParts = preg_split('/\s+/', trim($pi['name']), 2);
            $pi['first_name'] = $nameParts[0] ?? '';
            $pi['last_name'] = $nameParts[1] ?? '';
        }
        $normalized['first_name'] = $pi['first_name'] ?? $data['first_name'] ?? '';
        $normalized['last_name'] = $pi['last_name'] ?? $data['last_name'] ?? '';
        $normalized['email'] = $pi['email'] ?? $data['email'] ?? '';
        $normalized['phone'] = $pi['phone'] ?? $data['phone'] ?? '';
        $normalized['location'] = $pi['location'] ?? $data['location'] ?? '';
        $normalized['linkedin'] = $pi['linkedin'] ?? $data['linkedin'] ?? '';
        $normalized['github'] = $pi['github'] ?? $data['github'] ?? '';
        $normalized['website'] = $pi['website'] ?? $data['website'] ?? '';
        $normalized['title'] = $pi['title'] ?? $data['title'] ?? 'Imported CV';

        // Summary could be under "profile", "summary", "about", "objective"
        $normalized['summary'] = $data['profile'] ?? $data['summary'] ?? $data['about'] ?? $data['objective'] ?? '';

        // Experiences could be under "work_experience", "experience", "projects"
        $rawExperiences = $data['work_experience'] ?? $data['experience'] ?? $data['experiences'] ?? [];
        if (is_array($rawExperiences)) {
            $normalized['experiences'] = array_map(function ($exp) {
                if (isset($exp['name']) && ! isset($exp['company'])) {
                    // This is a project, not a work experience
                    return [
                        'company' => $exp['name'] ?? '',
                        'title' => '',
                        'location' => '',
                        'start_date' => null,
                        'end_date' => null,
                        'is_current' => false,
                        'description' => $exp['description'] ?? '',
                        'achievements' => $exp['achievements'] ?? [],
                    ];
                }

                return [
                    'company' => $exp['company'] ?? '',
                    'title' => $exp['title'] ?? $exp['role'] ?? '',
                    'location' => $exp['location'] ?? '',
                    'start_date' => $exp['start_date'] ?? null,
                    'end_date' => $exp['end_date'] ?? null,
                    'is_current' => $exp['is_current'] ?? false,
                    'description' => $exp['description'] ?? '',
                    'achievements' => $exp['achievements'] ?? [],
                ];
            }, $rawExperiences);
        }

        // Skills could be nested object {frontend: [...], backend: [...]} or flat array
        $rawSkills = $data['skills'] ?? [];
        if (isset($rawSkills['frontend']) || isset($rawSkills['backend']) || isset($rawSkills['tools'])) {
            $normalized['skills'] = [];
            foreach ($rawSkills as $category => $names) {
                if (is_array($names)) {
                    foreach ($names as $name) {
                        if (is_string($name) && strlen(trim($name)) > 0) {
                            $normalized['skills'][] = [
                                'name' => $name,
                                'category' => $category,
                                'level' => 'intermediate',
                            ];
                        }
                    }
                }
            }
        } elseif (is_array($rawSkills)) {
            $normalized['skills'] = array_map(function ($skill) {
                if (is_string($skill)) {
                    return ['name' => $skill, 'category' => 'general', 'level' => 'intermediate'];
                }

                return [
                    'name' => $skill['name'] ?? '',
                    'category' => $skill['category'] ?? 'general',
                    'level' => $skill['level'] ?? 'intermediate',
                ];
            }, $rawSkills);
        }

        // Education
        $normalized['educations'] = $data['education'] ?? $data['educations'] ?? [];

        // Certifications
        $normalized['certifications'] = $data['certifications'] ?? [];

        // Projects
        $normalized['projects'] = $data['projects'] ?? $data['project'] ?? [];

        // Languages
        $normalized['languages'] = $data['languages'] ?? $data['language'] ?? [];

        return $normalized;
    }

    private function importExperiences(Cv $cv, array|string $data): void
    {
        $experiences = is_array($data) ? $data : json_decode($data, true);

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

    private function importSkills(Cv $cv, array|string $data): void
    {
        $skills = is_array($data) ? $data : json_decode($data, true);

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

    private function importEducations(Cv $cv, array|string $data): void
    {
        $educations = is_array($data) ? $data : json_decode($data, true);

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

    private function importCertifications(Cv $cv, array|string $data): void
    {
        $certifications = is_array($data) ? $data : json_decode($data, true);

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

    private function importProjects(Cv $cv, array|string $data): void
    {
        $projects = is_array($data) ? $data : json_decode($data, true);

        if (! is_array($projects)) {
            return;
        }

        foreach ($projects as $index => $proj) {
            CvProject::create([
                'cv_id' => $cv->id,
                'name' => $proj['name'] ?? '',
                'description' => $proj['description'] ?? '',
                'key_achievements' => $proj['key_achievements'] ?? [],
                'project_url' => $proj['project_url'] ?? '',
                'github_url' => $proj['github_url'] ?? '',
                'start_date' => $proj['start_date'] ?? null,
                'end_date' => $proj['end_date'] ?? null,
                'sort_order' => $index,
            ]);
        }
    }

    private function importLanguages(Cv $cv, array|string $data): void
    {
        $languages = is_array($data) ? $data : json_decode($data, true);

        if (! is_array($languages)) {
            return;
        }

        foreach ($languages as $index => $lang) {
            CvLanguage::create([
                'cv_id' => $cv->id,
                'language' => $lang['language'] ?? '',
                'proficiency' => $lang['proficiency'] ?? 'intermediate',
                'sort_order' => $index,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.cv-builder');
    }
}
