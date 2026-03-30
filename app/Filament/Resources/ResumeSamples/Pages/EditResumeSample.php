<?php

namespace App\Filament\Resources\ResumeSamples\Pages;

use App\Filament\Resources\ResumeSamples\ResumeSampleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResumeSample extends EditRecord
{
    protected static string $resource = ResumeSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
