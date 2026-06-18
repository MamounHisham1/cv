<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvVersion;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('My CVs')]
class Drafts extends Component
{
    /** Confirmation modal state: which CV a pending action targets. */
    public ?int $confirmingDelete = null;

    public ?int $confirmingUnshare = null;

    /** Version-history modal: the CV being inspected + versions list. */
    public ?int $viewingVersionsFor = null;

    public function duplicate(int $cvId): void
    {
        $cv = $this->ownedCv($cvId);
        $copy = $cv->duplicate();

        $this->dispatch('notify', message: 'CV duplicated as "'.$copy->title.'".', type: 'success');
    }

    public function confirmDelete(int $cvId): void
    {
        $this->confirmingDelete = $this->ownedCv($cvId)?->id;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        $cv = $this->ownedCv($this->confirmingDelete);
        if ($cv) {
            $cv->delete();
            $this->dispatch('notify', message: 'CV deleted.', type: 'success');
        }

        $this->confirmingDelete = null;
    }

    public function share(int $cvId): void
    {
        $cv = $this->ownedCv($cvId);
        if (! $cv) {
            return;
        }
        $cv->enableSharing();

        $this->dispatch('notify', message: 'Public share link created.', type: 'success');
    }

    public function confirmUnshare(int $cvId): void
    {
        $this->confirmingUnshare = $this->ownedCv($cvId)?->id;
    }

    public function unshare(): void
    {
        if (! $this->confirmingUnshare) {
            return;
        }

        $cv = $this->ownedCv($this->confirmingUnshare);
        if ($cv) {
            $cv->disableSharing();
            $this->dispatch('notify', message: 'Share link disabled.', type: 'success');
        }

        $this->confirmingUnshare = null;
    }

    public function cancelConfirm(): void
    {
        $this->confirmingDelete = null;
        $this->confirmingUnshare = null;
    }

    public function viewVersions(int $cvId): void
    {
        $cv = $this->ownedCv($cvId);
        if (! $cv) {
            return;
        }
        $this->viewingVersionsFor = $cv->id;
    }

    public function closeVersions(): void
    {
        $this->viewingVersionsFor = null;
    }

    public function revertTo(int $versionId): void
    {
        $version = CvVersion::with('cv')->find($versionId);
        if (! $version || $version->cv->user_id !== Auth::id()) {
            return;
        }

        // Capture the current state before reverting, so the user can
        // undo the revert itself.
        CvVersion::snapshotIfChanged($version->cv, 'Before revert');
        $version->revert();

        $this->viewingVersionsFor = null;
        $this->dispatch('notify', message: 'CV restored to selected version.', type: 'success');
    }

    public function render()
    {
        $user = Auth::user();

        // No CVs yet → bounce to onboarding, mirroring the old closure.
        if (! $user->cvs()->exists()) {
            return $this->redirect(route('cv.builder', ['onboarding' => 1]), navigate: true);
        }

        $cvs = $user->cvs()
            ->with(['experiences', 'skills', 'certifications'])
            ->latest()
            ->get();

        $versionsFor = null;
        if ($this->viewingVersionsFor) {
            $versionsFor = CvVersion::where('cv_id', $this->viewingVersionsFor)
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('livewire.drafts', ['cvs' => $cvs, 'versionsFor' => $versionsFor]);
    }

    private function ownedCv(int $cvId): ?Cv
    {
        return Cv::where('user_id', Auth::id())->find($cvId);
    }
}
