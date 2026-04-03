<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvCertification;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class CvCertificationsManager extends Component
{
    public ?Cv $cv = null;

    public array $certifications = [];

    // Form state
    public bool $showForm = false;

    public ?int $editingId = null;

    public array $form = [
        'name' => '',
        'issuing_organization' => '',
        'issue_date' => '',
        'expiration_date' => '',
        'credential_id' => '',
        'credential_url' => '',
    ];

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadCertifications();
    }

    #[On('cv-saved')]
    public function onCvSaved($cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadCertifications();
        }
    }

    public function loadCertifications(): void
    {
        if ($this->cv && $this->cv->exists) {
            $this->certifications = $this->cv->certifications->toArray();
        }
    }

    public function addCertification(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function editCertification(int $id): void
    {
        $cert = CvCertification::findOrFail($id);

        if ($cert->cv_id !== $this->cv->id) {
            return;
        }

        $this->form = [
            'name' => $cert->name,
            'issuing_organization' => $cert->issuing_organization,
            'issue_date' => $cert->issue_date?->format('Y-m-d') ?? '',
            'expiration_date' => $cert->expiration_date?->format('Y-m-d') ?? '',
            'credential_id' => $cert->credential_id ?? '',
            'credential_url' => $cert->credential_url ?? '',
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveCertification(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.issuing_organization' => 'required|string|max:255',
            'form.issue_date' => 'nullable|date',
            'form.expiration_date' => 'nullable|date|after_or_equal:form.issue_date',
            'form.credential_id' => 'nullable|string|max:255',
            'form.credential_url' => 'nullable|url|max:255',
        ]);

        $data = array_merge($this->form, [
            'cv_id' => $this->cv->id,
            'sort_order' => $this->editingId ? null : count($this->certifications),
        ]);

        if ($this->editingId) {
            CvCertification::find($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Certification updated successfully!', type: 'success');
        } else {
            CvCertification::create($data);
            $this->dispatch('notify', message: 'Certification added successfully!', type: 'success');
        }

        $this->loadCertifications();
        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('cv-updated');
    }

    public function deleteCertification(int $id): void
    {
        $cert = CvCertification::findOrFail($id);

        if ($cert->cv_id !== $this->cv->id) {
            return;
        }

        $cert->delete();
        $this->loadCertifications();

        $this->dispatch('notify', message: 'Certification deleted successfully!', type: 'success');
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
                'form.issuing_organization' => 'required|string|max:255',
                'form.issue_date' => 'nullable|date',
                'form.expiration_date' => 'nullable|date|after_or_equal:form.issue_date',
                'form.credential_id' => 'nullable|string|max:255',
                'form.credential_url' => 'nullable|url|max:255',
            ]);

            CvCertification::find($this->editingId)->update($this->form);
            $this->loadCertifications();
            $this->dispatch('cv-updated');
        } catch (ValidationException $e) {
        }
    }

    public function handleSort(string $id, int $position): void
    {
        $item = CvCertification::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }
        $items = $this->cv->certifications()->orderBy('sort_order')->get()->values();
        $items = $items->reject(fn ($i) => $i->id == $id)->values();
        $items->splice($position, 0, [$item]);
        foreach ($items as $index => $i) {
            $i->update(['sort_order' => $index]);
        }

        $this->loadCertifications();
    }

    public function moveUp(int $id): void
    {
        $item = CvCertification::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->certifications()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex > 0) {
            $prevItem = $items[$currentIndex - 1];
            $item->update(['sort_order' => $currentIndex - 1]);
            $prevItem->update(['sort_order' => $currentIndex]);
            $this->loadCertifications();
        }
    }

    public function moveDown(int $id): void
    {
        $item = CvCertification::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->certifications()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex < $items->count() - 1) {
            $nextItem = $items[$currentIndex + 1];
            $item->update(['sort_order' => $currentIndex + 1]);
            $nextItem->update(['sort_order' => $currentIndex]);
            $this->loadCertifications();
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
            'name' => '',
            'issuing_organization' => '',
            'issue_date' => '',
            'expiration_date' => '',
            'credential_id' => '',
            'credential_url' => '',
        ];
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.cv-certifications-manager');
    }
}
