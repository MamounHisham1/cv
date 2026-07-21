<?php

namespace App\Ai\Tools;

use App\Models\Cv;
use App\Services\ProposedCvChanges;

trait InteractsWithCv
{
    protected ?Cv $cv = null;

    public function setCv(?Cv $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * The change buffer the AI write tools stage proposed edits into.
     * Resolved from the container (a singleton per request) so every tool
     * shares the same buffer the Livewire component reads after the run.
     */
    protected function proposedChanges(): ProposedCvChanges
    {
        return app(ProposedCvChanges::class);
    }
}
