<?php

namespace App\Filament\Resources\CvEvaluations\Pages;

use App\Filament\Resources\CvEvaluations\CvEvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvEvaluations extends ListRecords
{
    protected static string $resource = CvEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
