<?php

namespace App\Filament\Resources\InterviewEvaluations\Pages;

use App\Filament\Resources\InterviewEvaluations\InterviewEvaluationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInterviewEvaluation extends EditRecord
{
    protected static string $resource = InterviewEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
