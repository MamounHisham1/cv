<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvSkill;
use Livewire\Attributes\On;
use Livewire\Component;

class CvSkillsManager extends Component
{
    public ?Cv $cv = null;
    public array $skills = [];

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;

    public array $form = [
        'name' => '',
        'category' => 'general',
        'level' => 'intermediate',
    ];

    public array $categories = [
        'general' => 'General',
        'technical' => 'Technical Skills',
        'software' => 'Software & Tools',
        'industry' => 'Industry Knowledge',
        'soft' => 'Soft Skills',
    ];

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

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadSkills();
    }

    #[On('cv-saved')]
    public function onCvSaved(int $cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadSkills();
        }
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

        $this->form = [
            'name' => $skill->name,
            'category' => $skill->category,
            'level' => $skill->level ?? 'intermediate',
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveSkill(): void
    {
        if (!$this->cv || !$this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');
            return;
        }

        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.category' => 'required|string|in:' . implode(',', array_keys($this->categories)),
            'form.level' => 'nullable|string|in:' . implode(',', array_keys($this->levels)),
        ]);

        $data = array_merge($this->form, [
            'cv_id' => $this->cv->id,
            'sort_order' => $this->editingId ? null : $this->getNextSortOrder(),
        ]);

        if ($this->editingId) {
            CvSkill::find($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Skill updated successfully!', type: 'success');
        } else {
            CvSkill::create($data);
            $this->dispatch('notify', message: 'Skill added successfully!', type: 'success');
        }

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
        if (!$this->cv || !$this->cv->exists) {
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

    private function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'category' => 'general',
            'level' => 'intermediate',
        ];
        $this->editingId = null;
    }

    private function getNextSortOrder(): int
    {
        if (!$this->cv) {
            return 0;
        }
        return $this->cv->skills()->count();
    }

    public function render()
    {
        return view('livewire.cv-skills-manager');
    }
}
