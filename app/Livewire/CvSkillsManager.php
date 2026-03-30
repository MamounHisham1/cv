<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvSkill;
use App\Models\UserSkillCategory;
use Livewire\Attributes\On;
use Livewire\Component;

class CvSkillsManager extends Component
{
    public ?Cv $cv = null;

    public array $skills = [];

    public bool $showForm = false;

    public ?int $editingId = null;

    public array $form = [
        'name' => '',
        'category' => '',
        'level' => 'intermediate',
    ];

    public array $categories = [];

    public array $levels = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
        'expert' => 'Expert',
    ];

    public array $commonSkills = [
        'Microsoft Office', 'Excel', 'PowerPoint', 'Word',
        'Google Workspace', 'Slack', 'Teams', 'Zoom',
        'Project Management', 'Agile', 'Scrum', 'Kanban',
        'Data Analysis', 'SQL', 'Python', 'R',
        'Salesforce', 'HubSpot', 'SAP', 'Oracle',
    ];

    private const PRESET_CATEGORIES = [
        'general' => 'General',
        'technical' => 'Technical Skills',
        'software' => 'Software & Tools',
        'industry' => 'Industry Knowledge',
        'soft' => 'Soft Skills',
    ];

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadCategories();
        $this->loadSkills();
    }

    #[On('cv-saved')]
    public function onCvSaved(int $cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadSkills();
        } elseif (! $this->cv || ! $this->cv->exists) {
            $this->cv = Cv::find($cvId);
            $this->loadSkills();
        }
    }

    public function loadCategories(): void
    {
        $user = auth()->user();

        if (! $user) {
            $this->categories = self::PRESET_CATEGORIES;

            return;
        }

        $custom = $user->skillCategories()->pluck('name', 'name')->toArray();
        $this->categories = array_merge(self::PRESET_CATEGORIES, $custom);
    }

    public function loadSkills(): void
    {
        if ($this->cv && $this->cv->exists) {
            $this->skills = $this->cv->skills->groupBy('category')->toArray();
        }
    }

    public function addSkill(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function editSkill(int $id): void
    {
        $skill = CvSkill::findOrFail($id);

        if ($skill->cv_id !== $this->cv->id) {
            return;
        }

        $categoryLabel = $this->categories[$skill->category] ?? ucfirst($skill->category);

        $this->form = [
            'name' => $skill->name,
            'category' => $categoryLabel,
            'level' => $skill->level ?? 'intermediate',
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveSkill(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.category' => 'required|string|max:50',
            'form.level' => 'nullable|string|in:'.implode(',', array_keys($this->levels)),
        ]);

        $input = trim($this->form['category']);
        $categoryKey = null;

        foreach ($this->categories as $key => $label) {
            if (strtolower($label) === strtolower($input)) {
                $categoryKey = $key;
                break;
            }
        }

        $categoryKey = $categoryKey ?? strtolower($input);
        $categoryLabel = $this->categories[$categoryKey] ?? ucfirst($categoryKey);

        if (! array_key_exists($categoryKey, self::PRESET_CATEGORIES)) {
            $this->persistCustomCategory($categoryLabel);
        }

        $data = [
            'cv_id' => $this->cv->id,
            'name' => $this->form['name'],
            'category' => $categoryKey,
            'level' => $this->form['level'],
            'sort_order' => $this->editingId ? null : $this->getNextSortOrder(),
        ];

        if ($this->editingId) {
            CvSkill::find($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Skill updated successfully!', type: 'success');
        } else {
            CvSkill::create($data);
            $this->dispatch('notify', message: 'Skill added successfully!', type: 'success');
        }

        $this->loadCategories();
        $this->loadSkills();
        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('cv-updated');
    }

    public function deleteSkill(int $id): void
    {
        $skill = CvSkill::findOrFail($id);

        if ($skill->cv_id !== $this->cv->id) {
            return;
        }

        $skill->delete();
        $this->loadSkills();

        $this->dispatch('notify', message: 'Skill deleted successfully!', type: 'success');
        $this->dispatch('cv-updated');
    }

    public function quickAddSkill(string $skillName): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        CvSkill::create([
            'cv_id' => $this->cv->id,
            'name' => $skillName,
            'category' => 'software',
            'level' => 'intermediate',
            'sort_order' => $this->getNextSortOrder(),
        ]);

        $this->loadSkills();
        $this->dispatch('notify', message: "{$skillName} added!", type: 'success');
        $this->dispatch('cv-updated');
    }

    public function cancelEdit(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function persistCustomCategory(string $label): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        UserSkillCategory::firstOrCreate([
            'user_id' => $user->id,
            'name' => $label,
        ]);
    }

    private function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'category' => '',
            'level' => 'intermediate',
        ];
        $this->editingId = null;
    }

    private function getNextSortOrder(): int
    {
        if (! $this->cv) {
            return 0;
        }

        return $this->cv->skills()->count();
    }

    public function render()
    {
        return view('livewire.cv-skills-manager');
    }
}
