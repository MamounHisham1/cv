<?php

namespace App\Filament\Resources\CvEvaluations\Pages;

use App\Filament\Resources\CvEvaluations\CvEvaluationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCvEvaluation extends EditRecord
{
    protected static string $resource = CvEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
