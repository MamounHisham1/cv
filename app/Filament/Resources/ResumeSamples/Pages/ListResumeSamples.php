<?php

namespace App\Filament\Resources\ResumeSamples\Pages;

use App\Filament\Resources\ResumeSamples\ResumeSampleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResumeSamples extends ListRecords
{
    protected static string $resource = ResumeSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
