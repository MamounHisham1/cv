<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvProject;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CvProjectManager extends Component
{
    public ?Cv $cv = null;

    public array $projects = [];

    public bool $showForm = false;

    public ?int $editingId = null;

    public array $form = [
        'name' => '',
        'description' => '',
        'key_achievements' => [],
        'project_url' => '',
        'github_url' => '',
        'start_date' => '',
        'end_date' => '',
    ];

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadProjects();
    }

    #[On('cv-saved')]
    public function onCvSaved(int $cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadProjects();
        } elseif (! $this->cv || ! $this->cv->exists) {
            $this->cv = Cv::find($cvId);
            $this->loadProjects();
        }
    }

    public function loadProjects(): void
    {
        if ($this->cv && $this->cv->exists) {
            $this->projects = $this->cv->projects->toArray();
        }
    }

    public function addProject(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function editProject(int $id): void
    {
        $project = CvProject::findOrFail($id);

        if ($project->cv_id !== $this->cv->id) {
            return;
        }

        $this->form = [
            'name' => $project->name,
            'description' => $project->description ?? '',
            'key_achievements' => $project->key_achievements ?? [],
            'project_url' => $project->project_url ?? '',
            'github_url' => $project->github_url ?? '',
            'start_date' => $project->start_date?->format('Y-m-d') ?? '',
            'end_date' => $project->end_date?->format('Y-m-d') ?? '',
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveProject(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.description' => 'required|string|max:5000',
            'form.key_achievements' => 'nullable|array',
            'form.project_url' => 'nullable|url|max:255',
            'form.github_url' => 'nullable|url|max:255',
            'form.start_date' => 'nullable|date',
            'form.end_date' => 'nullable|date|after_or_equal:form.start_date',
        ]);

        $data = array_merge($this->form, [
            'cv_id' => $this->cv->id,
            'sort_order' => $this->editingId ? ($project->sort_order ?? count($this->projects)) : count($this->projects),
        ]);

        if ($this->editingId) {
            $updateData = $data;
            unset($updateData['sort_order']);
            CvProject::find($this->editingId)->update($updateData);
            $this->dispatch('notify', message: 'Project updated successfully!', type: 'success');
        } else {
            CvProject::create($data);
            $this->dispatch('notify', message: 'Project added successfully!', type: 'success');
        }

        $this->loadProjects();
        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('cv-updated');
    }

    public function deleteProject(int $id): void
    {
        $project = CvProject::findOrFail($id);

        if ($project->cv_id !== $this->cv->id) {
            return;
        }

        $project->delete();
        $this->loadProjects();

        $this->dispatch('notify', message: 'Project deleted successfully!', type: 'success');
        $this->dispatch('cv-updated');
    }

    public function updated($property): void
    {
        if (! str_starts_with($property, 'form.') || ! $this->editingId) {
            return;
        }

        $this->autosave();
    }

    public function autosave(): void
    {
        try {
            $this->validate([
                'form.name' => 'required|string|max:255',
                'form.description' => 'required|string|max:5000',
                'form.key_achievements' => 'nullable|array',
                'form.project_url' => 'nullable|url|max:255',
                'form.github_url' => 'nullable|url|max:255',
                'form.start_date' => 'nullable|date',
                'form.end_date' => 'nullable|date|after_or_equal:form.start_date',
            ]);

            CvProject::find($this->editingId)->update($this->form);
            $this->loadProjects();
            $this->dispatch('cv-updated');
        } catch (ValidationException $e) {
        }
    }

    public function handleSort(string $id, int $position): void
    {
        $item = CvProject::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }
        $items = $this->cv->projects()->orderBy('sort_order')->get()->values();
        $items = $items->reject(fn ($i) => $i->id == $id)->values();
        $items->splice($position, 0, [$item]);
        foreach ($items as $index => $i) {
            $i->update(['sort_order' => $index]);
        }

        $this->loadProjects();
    }

    public function moveUp(int $id): void
    {
        $item = CvProject::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->projects()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex > 0) {
            $prevItem = $items[$currentIndex - 1];
            $item->update(['sort_order' => $currentIndex - 1]);
            $prevItem->update(['sort_order' => $currentIndex]);
            $this->loadProjects();
        }
    }

    public function moveDown(int $id): void
    {
        $item = CvProject::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->projects()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex < $items->count() - 1) {
            $nextItem = $items[$currentIndex + 1];
            $item->update(['sort_order' => $currentIndex + 1]);
            $nextItem->update(['sort_order' => $currentIndex]);
            $this->loadProjects();
        }
    }

    public function addAchievement(): void
    {
        $this->form['key_achievements'][] = '';
    }

    public function removeAchievement(int $index): void
    {
        unset($this->form['key_achievements'][$index]);
        $this->form['key_achievements'] = array_values($this->form['key_achievements']);
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
            'description' => '',
            'key_achievements' => [],
            'project_url' => '',
            'github_url' => '',
            'start_date' => '',
            'end_date' => '',
        ];
        $this->editingId = null;
    }

    public function render(): View
    {
        return view('livewire.cv-project-manager');
    }
}
