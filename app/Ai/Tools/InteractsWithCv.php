<?php

namespace App\Ai\Tools;

use App\Models\Cv;

trait InteractsWithCv
{
    protected ?Cv $cv = null;

    public function setCv(?Cv $cv): static
    {
        $this->cv = $cv;

        return $this;
    }
}
