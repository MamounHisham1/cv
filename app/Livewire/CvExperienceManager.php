<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvExperience;
use Livewire\Attributes\On;
use Livewire\Component;

class CvExperienceManager extends Component
{
    public ?Cv $cv = null;
    public array $experiences = [];

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;

    public array $form = [
        'company' => '',
        'title' => '',
        'location' => '',
        'start_date' => '',
        'end_date' => '',
        'is_current' => false,
        'description' => '',
        'technologies' => [],
        'achievements' => [],
    ];

    public array $commonSkills = [
        'Project Management', 'Team Leadership', 'Agile', 'Scrum',
        'Data Analysis', 'Strategic Planning', 'Budget Management',
        'Client Relations', 'Problem Solving', 'Communication',
        'Microsoft Office', 'Google Workspace', 'Salesforce', 'SAP',
    ];

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadExperiences();
    }

    #[On('cv-saved')]
    public function onCvSaved(int $cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadExperiences();
        }
    }

    public function loadExperiences(): void
    {
        if ($this->cv && $this->cv->exists) {
            $this->experiences = $this->cv->experiences->toArray();
        }
    }

    public function addExperience(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function editExperience(int $id): void
    {
        $experience = CvExperience::findOrFail($id);

        if ($experience->cv_id !== $this->cv->id) {
            return;
        }

        $this->form = [
            'company' => $experience->company,
            'title' => $experience->title,
            'location' => $experience->location ?? '',
            'start_date' => $experience->start_date?->format('Y-m-d') ?? '',
            'end_date' => $experience->end_date?->format('Y-m-d') ?? '',
            'is_current' => $experience->is_current,
            'description' => $experience->description,
            'technologies' => $experience->technologies ?? [],
            'achievements' => $experience->achievements ?? [],
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveExperience(): void
    {
        if (!$this->cv || !$this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');
            return;
        }

        $this->validate([
            'form.company' => 'required|string|max:255',
            'form.title' => 'required|string|max:255',
            'form.location' => 'nullable|string|max:255',
            'form.start_date' => 'required|date',
            'form.end_date' => 'nullable|date|after_or_equal:form.start_date',
            'form.is_current' => 'boolean',
            'form.description' => 'required|string|max:5000',
            'form.technologies' => 'nullable|array',
            'form.achievements' => 'nullable|array',
        ]);

        $data = array_merge($this->form, [
            'cv_id' => $this->cv->id,
            'sort_order' => $this->editingId ? null : count($this->experiences),
        ]);

        if ($this->editingId) {
            CvExperience::find($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Experience updated successfully!', type: 'success');
        } else {
            CvExperience::create($data);
            $this->dispatch('notify', message: 'Experience added successfully!', type: 'success');
        }

        $this->loadExperiences();
        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('cv-updated');
    }

    public function deleteExperience(int $id): void
    {
        $experience = CvExperience::findOrFail($id);

        if ($experience->cv_id !== $this->cv->id) {
            return;
        }

        $experience->delete();
        $this->loadExperiences();

        $this->dispatch('notify', message: 'Experience deleted successfully!', type: 'success');
        $this->dispatch('cv-updated');
    }

    public function addAchievement(): void
    {
        $this->form['achievements'][] = '';
    }

    public function removeAchievement(int $index): void
    {
        unset($this->form['achievements'][$index]);
        $this->form['achievements'] = array_values($this->form['achievements']);
    }

    public function cancelEdit(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form = [
            'company' => '',
            'title' => '',
            'location' => '',
            'start_date' => '',
            'end_date' => '',
            'is_current' => false,
            'description' => '',
            'technologies' => [],
            'achievements' => [],
        ];
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.cv-experience-manager');
    }
}
