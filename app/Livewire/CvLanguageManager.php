<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvLanguage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CvLanguageManager extends Component
{
    public ?Cv $cv = null;

    public array $languages = [];

    public bool $showForm = false;

    public ?int $editingId = null;

    public array $form = [
        'language' => '',
        'proficiency' => 'intermediate',
    ];

    public array $proficiencies = [
        'beginner' => 'Beginner',
        'elementary' => 'Elementary',
        'intermediate' => 'Intermediate',
        'upper_intermediate' => 'Upper Intermediate',
        'advanced' => 'Advanced',
        'fluent' => 'Fluent',
        'native' => 'Native',
    ];

    public array $commonLanguages = [
        'Arabic', 'English', 'French', 'German', 'Spanish', 'Portuguese',
        'Italian', 'Russian', 'Japanese', 'Chinese', 'Korean', 'Turkish',
        'Hindi', 'Urdu', 'Persian', 'Swedish', 'Dutch',
    ];

    public function mount(?Cv $cv = null): void
    {
        $this->cv = $cv;
        $this->loadLanguages();
    }

    #[On('cv-saved')]
    public function onCvSaved($cvId): void
    {
        if ($this->cv && $this->cv->id === $cvId) {
            $this->cv->refresh();
            $this->loadLanguages();
        } elseif (! $this->cv || ! $this->cv->exists) {
            $this->cv = Cv::find($cvId);
            $this->loadLanguages();
        }
    }

    public function loadLanguages(): void
    {
        if ($this->cv && $this->cv->exists) {
            $this->languages = $this->cv->languages()->orderBy('sort_order')->get()->toArray();
        }
    }

    public function addLanguage(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function editLanguage(int $id): void
    {
        $language = CvLanguage::findOrFail($id);

        if ($language->cv_id !== $this->cv->id) {
            return;
        }

        $this->form = [
            'language' => $language->language,
            'proficiency' => $language->proficiency,
        ];

        $this->editingId = $id;
        $this->showForm = true;
    }

    public function saveLanguage(): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        $this->validate([
            'form.language' => 'required|string|max:100',
            'form.proficiency' => 'required|string|in:'.implode(',', array_keys($this->proficiencies)),
        ]);

        $data = array_merge($this->form, [
            'cv_id' => $this->cv->id,
            'sort_order' => count($this->languages),
        ]);

        if ($this->editingId) {
            $updateData = $data;
            unset($updateData['sort_order']);
            CvLanguage::find($this->editingId)->update($updateData);
            $this->dispatch('notify', message: 'Language updated successfully!', type: 'success');
        } else {
            CvLanguage::create($data);
            $this->dispatch('notify', message: 'Language added successfully!', type: 'success');
        }

        $this->loadLanguages();
        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('cv-updated');
    }

    public function deleteLanguage(int $id): void
    {
        $language = CvLanguage::findOrFail($id);

        if ($language->cv_id !== $this->cv->id) {
            return;
        }

        $language->delete();
        $this->loadLanguages();

        $this->dispatch('notify', message: 'Language deleted successfully!', type: 'success');
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
                'form.language' => 'required|string|max:100',
                'form.proficiency' => 'required|string|in:'.implode(',', array_keys($this->proficiencies)),
            ]);

            CvLanguage::find($this->editingId)->update($this->form);
            $this->loadLanguages();
            $this->dispatch('cv-updated');
        } catch (ValidationException $e) {
        }
    }

    public function handleSort(string $id, int $position): void
    {
        $item = CvLanguage::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }
        $items = $this->cv->languages()->orderBy('sort_order')->get()->values();
        $items = $items->reject(fn ($i) => $i->id == $id)->values();
        $items->splice($position, 0, [$item]);
        foreach ($items as $index => $i) {
            $i->update(['sort_order' => $index]);
        }

        $this->loadLanguages();
    }

    public function moveUp(int $id): void
    {
        $item = CvLanguage::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->languages()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex > 0) {
            $prevItem = $items[$currentIndex - 1];
            $item->update(['sort_order' => $currentIndex - 1]);
            $prevItem->update(['sort_order' => $currentIndex]);
            $this->loadLanguages();
        }
    }

    public function moveDown(int $id): void
    {
        $item = CvLanguage::findOrFail($id);
        if ($item->cv_id !== $this->cv->id) {
            return;
        }

        $items = $this->cv->languages()->orderBy('sort_order')->get()->values();
        $currentIndex = $items->search(fn ($i) => $i->id === $id);

        if ($currentIndex < $items->count() - 1) {
            $nextItem = $items[$currentIndex + 1];
            $item->update(['sort_order' => $currentIndex + 1]);
            $nextItem->update(['sort_order' => $currentIndex]);
            $this->loadLanguages();
        }
    }

    public function quickAddLanguage(string $languageName): void
    {
        if (! $this->cv || ! $this->cv->exists) {
            $this->dispatch('notify', message: 'Please save your personal information first.', type: 'error');

            return;
        }

        CvLanguage::create([
            'cv_id' => $this->cv->id,
            'language' => $languageName,
            'proficiency' => 'intermediate',
            'sort_order' => count($this->languages),
        ]);

        $this->loadLanguages();
        $this->dispatch('notify', message: "{$languageName} added!", type: 'success');
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
            'language' => '',
            'proficiency' => 'intermediate',
        ];
        $this->editingId = null;
    }

    public function render(): View
    {
        return view('livewire.cv-language-manager');
    }
}
