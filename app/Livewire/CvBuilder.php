<?php

namespace App\Livewire;

use App\Cv\IndustryPacks\IndustryPacks;
use App\CvTemplates;
use App\Jobs\ParseCvWithAi;
use App\Models\Cv;
use App\Services\CreditManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use League\Flysystem\UnableToRetrieveMetadata;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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

    public array $industryPacks = [];

    public ?string $selectedIndustryPack = null;

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

    /**
     * The blank personal-info shape shared by onboarding, scratch creation,
     * and CV import.
     */
    private function emptyPersonalInfo(): array
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'location' => '',
            'linkedin' => '',
            'website' => '',
            'github' => '',
        ];
    }

    public string $summary = '';

    public string $title = '';

    public string $selectedTemplate = 'professional-classic';

    /**
     * Temporary Livewire file upload for the optional profile photo
     * (used by templates that render an avatar: creative + warm).
     */
    public $photoUpload = null;

    // Form states
    public bool $showPreview = true;

    // Onboarding modal state
    public bool $showOnboardingModal = false;

    public $uploadedFile = null;

    public bool $isImporting = false;

    public ?string $importError = null;

    /** 'idle' | 'importing' | 'completed' | 'failed' */
    public string $importStatus = 'idle';

    public ?string $tempFilePath = null;

    public function mount(?Cv $cv = null): void
    {
        if ($cv && $cv->exists) {
            $this->authorize('update', $cv);
            // Eager-load all section relations once — without this, every
            // Livewire render of the builder page fires 5+ extra queries
            // through the `getCvDataProperty` accessor and the preview pane.
            $this->cv = $cv->load([
                'experiences',
                'educations',
                'skills',
                'certifications',
                'projects',
                'languages',
            ]);

            if ($cv->title === 'Importing...') {
                $this->importStatus = 'importing';
            }

            $this->loadCvData();
        } else {
            $this->cv = new Cv;
            $this->personalInfo['email'] = Auth::user()->email;

            // Show onboarding when the user has no CVs yet or explicitly requested
            $hasCvs = Auth::user()->cvs()->exists();
            $this->stage = (! $hasCvs || request()->has('onboarding')) ? 'onboarding' : 'builder';
        }

        $this->templates = $this->getAvailableTemplates();
        $this->industryPacks = IndustryPacks::options();
        $this->sections = $this->getSections();
    }

    public function hydrate(): void
    {
        if ($this->uploadedFile && ! $this->cv?->exists) {
            try {
                $this->uploadedFile->getSize();
            } catch (UnableToRetrieveMetadata) {
                $this->uploadedFile = null;
            }
        }
    }

    public function getUploadedFileSize(): ?string
    {
        if (! $this->uploadedFile) {
            return null;
        }

        try {
            $size = $this->uploadedFile->getSize();

            return number_format($size / 1024, 1).' KB';
        } catch (UnableToRetrieveMetadata) {
            return 'File size unavailable';
        }
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
        $this->personalInfo = array_merge(
            $this->emptyPersonalInfo(),
            ['email' => Auth::user()->email],
        );
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
            'personal_info' => array_merge(
                $this->emptyPersonalInfo(),
                ['email' => Auth::user()->email],
            ),
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

        $extension = strtolower($this->uploadedFile->getClientOriginalExtension());
        $originalName = $this->uploadedFile->getClientOriginalName();
        $fileSize = $this->uploadedFile->getSize();
        $this->tempFilePath = $this->uploadedFile->storeAs('temp/uploads', uniqid('cv_').'.'.$extension);

        $fullPath = storage_path('app/private/'.$this->tempFilePath);

        if (! file_exists($fullPath)) {
            $this->importError = 'Failed to store the uploaded file. Please try again.';
            $this->resetImportState();

            return;
        }

        $cv = Cv::create([
            'user_id' => Auth::id(),
            'title' => 'Importing...',
            'template_id' => $this->selectedTemplate,
            'personal_info' => array_merge(
                $this->emptyPersonalInfo(),
                ['email' => Auth::user()->email],
            ),
            'status' => 'draft',
        ]);

        ParseCvWithAi::dispatch(
            Auth::id(),
            $fullPath,
            $extension,
            $originalName,
            $fileSize,
            $cv->id,
        );

        $this->redirect(route('cv.edit', $cv));
    }

    public function checkImportStatus(): void
    {
        if (! $this->cv || ! $this->cv->exists || $this->importStatus !== 'importing') {
            return;
        }

        $this->cv->refresh();

        if ($this->cv->title === 'Import failed') {
            $this->importStatus = 'failed';
        } elseif ($this->cv->title !== 'Importing...') {
            $this->redirect(route('cv.edit', $this->cv));
        }
    }

    private function resetImportState(): void
    {
        $this->isImporting = false;
    }

    private function cleanupTempFile(): void
    {
        if ($this->tempFilePath) {
            Storage::delete($this->tempFilePath);
            $this->tempFilePath = null;
        }
    }

    #[On('cv-updated')]
    public function onCvUpdated($cvId = null): void
    {
        // If cvId is provided, only refresh if it matches our CV
        // If no cvId is provided, always refresh (event from sibling component)
        if ($cvId === null || ($this->cv && $this->cv->id === $cvId)) {
            if ($this->cv && $this->cv->exists) {
                $this->cv->refresh();
                $this->loadCvData();
            }
        }
    }

    public function loadCvData(): void
    {
        $this->title = $this->cv->title;
        $this->selectedTemplate = $this->cv->template_id;
        $this->selectedIndustryPack = $this->cv->industry_pack;
        $this->summary = $this->cv->summary ?? '';
        $this->personalInfo = array_merge($this->personalInfo, $this->cv->personal_info ?? []);
    }

    public function getAvailableTemplates(): array
    {
        return CvTemplates::all();
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

    public function updateIndustryPack(string $packId): void
    {
        if (! IndustryPacks::exists($packId)) {
            return;
        }

        $this->selectedIndustryPack = $packId;

        if ($this->cv->exists) {
            $this->cv->update(['industry_pack' => $packId]);
        }

        $this->dispatch('industry-pack-changed', packId: $packId);
    }

    /**
     * Store an uploaded profile photo on the public disk and attach its URL
     * to personal_info. Only rendered by photo-supporting templates.
     */
    public function savePhoto(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            return;
        }

        $data = $this->validate([
            'photoUpload' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->deleteStoredPhoto();

        $path = $data['photoUpload']->store("cv-photos/cv-{$this->cv->id}", 'public');

        $this->personalInfo['photo'] = Storage::url($path);
        $this->cv->update(['personal_info' => $this->personalInfo]);
        $this->photoUpload = null;

        $this->dispatch('notify', message: 'Profile photo updated.', type: 'success');
        $this->dispatch('cv-updated', cvId: $this->cv->id);
    }

    public function removePhoto(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            return;
        }

        $this->deleteStoredPhoto();
        unset($this->personalInfo['photo']);
        $this->cv->update(['personal_info' => $this->personalInfo]);
        $this->photoUpload = null;

        $this->dispatch('notify', message: 'Profile photo removed.', type: 'success');
        $this->dispatch('cv-updated', cvId: $this->cv->id);
    }

    private function deleteStoredPhoto(): void
    {
        if (empty($this->personalInfo['photo'])) {
            return;
        }

        $relative = ltrim(parse_url($this->personalInfo['photo'], PHP_URL_PATH) ?? '', '/');
        $onDisk = preg_replace('#^storage/#', '', $relative);

        if ($onDisk && Storage::disk('public')->exists($onDisk)) {
            Storage::disk('public')->delete($onDisk);
        }
    }

    /**
     * Contextual hint shown when the active template uses a feature the user
     * hasn't fully filled (profile photo, skill levels). Returns null when
     * there's nothing to hint about.
     *
     * @return array{icon: string, message: string, tab?: string}|null
     */
    #[Computed]
    public function templateHint(): ?array
    {
        $slug = $this->selectedTemplate;
        $name = CvTemplates::name($slug);

        if (CvTemplates::supports($slug, CvTemplates::FEATURE_PHOTO)
            && empty($this->personalInfo['photo'])
        ) {
            return [
                'icon' => 'user',
                'message' => "The {$name} template shows your photo. Add one under Personal Info (optional) — otherwise your initials are shown.",
                'tab' => 'personal',
            ];
        }

        if (CvTemplates::supports($slug, CvTemplates::FEATURE_SKILL_LEVELS)
            && $this->cv && $this->cv->exists
            && $this->cv->skills()->where(fn ($q) => $q->whereNull('level')->orWhere('level', ''))->exists()
        ) {
            return [
                'icon' => 'zap',
                'message' => "The {$name} template visualizes skill levels. Set a proficiency for each skill to make the most of it.",
                'tab' => 'skills',
            ];
        }

        return null;
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

        $orderedKeys = array_values(array_diff($orderedKeys, ['personal']));
        array_unshift($orderedKeys, 'personal');

        $this->cv->update(['section_order' => $orderedKeys]);
        $this->sections = $this->getSections();
    }

    public function handleSectionSort(string $sectionKey, int $position): void
    {
        if ($sectionKey === 'personal') {
            $this->dispatch('notify', message: 'Personal Information must stay at the top.', type: 'error');

            return;
        }

        $currentKeys = array_values(array_diff(array_keys($this->sections), ['personal']));
        $currentKeys = array_values(array_diff($currentKeys, [$sectionKey]));
        $position = max(0, $position - 1);
        array_splice($currentKeys, $position, 0, $sectionKey);
        array_unshift($currentKeys, 'personal');
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

    public function updated($property): void
    {
        if (str_starts_with($property, 'personalInfo.') || $property === 'title' || $property === 'summary') {
            $this->autosavePersonalInfo();
        }
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

            // Notify the live preview (and anything else listening) that the
            // CV changed, so it refreshes without a manual save button.
            $this->dispatch('cv-updated', cvId: $this->cv->id);
        } catch (ValidationException $e) {
        }
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

    public function render()
    {
        return view('livewire.cv-builder');
    }
}
