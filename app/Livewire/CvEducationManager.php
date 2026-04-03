<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvEducation;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CvEducationManager extends Component
{
    public ?Cv $cv = null;

    public array $educations = [];

    public bool $showForm = false;

    public ?int $editingId = null;

    public array $form = [
        'institution' => '',
        'degree' => '',
        'field_of_study' => '',
        'location' => '',
        'start_date' => '',
        'end_date' => '',
        'is_current' => false,
        'description' => '',
    ];

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadEducations();
    }

    #[On('cv-saved')]
    public function onCvSaved($cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadEducations();
        } elseif (! $this->cv || ! $this->cv->exists) {
            $this->cv = Cv::find($cvId);
            $this->loadEducations();
        }
    }

    public function loadEducations(): void
    {
        if ($this->cv && $this->cv->exists) {
            $this->educations = $this->cv->educations->toArray();
        }
    }

    public function addEducation(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function editEducation(int $id): void
    {
        $education = CvEducation::findOrFail($id);

        if ($education->cv_id !== $this->cv->id) {
            return;
        }

        $this->form = [
            'institution' => $education->institution,
            'degree' => $education->degree,
            'field_of_study' => $education->field_of_study ?? '',
            'location' => $education->location ?? '',
            'start_date' => $education->start_date?->format('Y-m-d') ?? '',
            'end_date' => $education->end_date?->format('Y-m-d') ?? '',
            'is_current' => $education->is_current,
            'description' => $education->description ?? '',
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveEducation(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        $this->validate([
            'form.institution' => 'required|string|max:255',
            'form.degree' => 'required|string|max:255',
            'form.field_of_study' => 'nullable|string|max:255',
            'form.location' => 'nullable|string|max:255',
            'form.start_date' => 'required|date',
            'form.end_date' => 'nullable|date|after_or_equal:form.start_date',
            'form.is_current' => 'boolean',
            'form.description' => 'nullable|string|max:2000',
        ]);

        $data = array_merge($this->form, [
            'cv_id' => $this->cv->id,
            'sort_order' => $this->editingId ? null : count($this->educations),
        ]);

        if ($this->editingId) {
            $updateData = $data;
            unset($updateData['sort_order']);
            CvEducation::find($this->editingId)->update($updateData);
            $this->dispatch('notify', message: 'Education updated successfully!', type: 'success');
        } else {
            CvEducation::create($data);
            $this->dispatch('notify', message: 'Education added successfully!', type: 'success');
        }

        $this->loadEducations();
        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('cv-updated');
    }

    public function deleteEducation(int $id): void
    {
        $education = CvEducation::findOrFail($id);

        if ($education->cv_id !== $this->cv->id) {
            return;
        }

        $education->delete();
        $this->loadEducations();

        $this->dispatch('notify', message: 'Education deleted successfully!', type: 'success');
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
                'form.institution' => 'required|string|max:255',
                'form.degree' => 'required|string|max:255',
                'form.field_of_study' => 'nullable|string|max:255',
                'form.location' => 'nullable|string|max:255',
                'form.start_date' => 'required|date',
                'form.end_date' => 'nullable|date|after_or_equal:form.start_date',
                'form.is_current' => 'boolean',
                'form.description' => 'nullable|string|max:2000',
            ]);

            CvEducation::find($this->editingId)->update($this->form);
            $this->loadEducations();
            $this->dispatch('cv-updated');
        } catch (ValidationException $e) {
        }
    }

    public function handleSort(string $id, int $position): void
    {
        $item = CvEducation::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }
        $items = $this->cv->educations()->orderBy('sort_order')->get()->values();
        $items = $items->reject(fn ($i) => $i->id == $id)->values();
        $items->splice($position, 0, [$item]);
        foreach ($items as $index => $i) {
            $i->update(['sort_order' => $index]);
        }

        $this->loadEducations();
    }

    public function moveUp(int $id): void
    {
        $item = CvEducation::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->educations()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex > 0) {
            $prevItem = $items[$currentIndex - 1];
            $item->update(['sort_order' => $currentIndex - 1]);
            $prevItem->update(['sort_order' => $currentIndex]);
            $this->loadEducations();
        }
    }

    public function moveDown(int $id): void
    {
        $item = CvEducation::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->educations()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex < $items->count() - 1) {
            $nextItem = $items[$currentIndex + 1];
            $item->update(['sort_order' => $currentIndex + 1]);
            $nextItem->update(['sort_order' => $currentIndex]);
            $this->loadEducations();
        }
    }

    public function cancelEdit(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form = [
            'institution' => '',
            'degree' => '',
            'field_of_study' => '',
            'location' => '',
            'start_date' => '',
            'end_date' => '',
            'is_current' => false,
            'description' => '',
        ];
        $this->editingId = null;
    }

    public function render(): View
    {
        return view('livewire.cv-education-manager');
    }
}
