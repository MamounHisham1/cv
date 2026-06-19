<?php

namespace App\Livewire;

use App\Models\Cv;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Reactive live preview of a CV. Renders the chosen Blade template
 * directly (no iframe) so Livewire swaps only the changed DOM — instant,
 * no page reload, no loading flash. The light CV theme is contained
 * inside a white surface so it doesn't bleed into the dark builder.
 *
 * Listens for cv-updated / template-changed / industry-pack-changed
 * events from the parent CvBuilder and refreshes its CV model.
 */
class CvLivePreview extends Component
{
    public int $cvId;

    public function mount(int $cvId): void
    {
        $this->cvId = $cvId;
    }

    #[On('cv-updated')]
    #[On('template-changed')]
    #[On('industry-pack-changed')]
    public function refreshPreview($cvId = null): void
    {
        // Only react to events targeting this CV (cv-updated carries the
        // id; the other events implicitly target the active builder CV).
        if ($cvId && (int) $cvId !== $this->cvId) {
            return;
        }
        // Re-rendering is automatic; this method exists so Livewire
        // re-fetches the CV in render() with fresh data.
    }

    public function render()
    {
        $cv = Cv::where('id', $this->cvId)
            ->where('user_id', Auth::id())
            ->with(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages'])
            ->firstOrFail();

        return view('livewire.cv-live-preview', ['cv' => $cv]);
    }
}
