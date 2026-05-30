<?php

namespace App\Filament\Resources\InterviewSessions\Pages;

use App\Filament\Resources\InterviewSessions\InterviewSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInterviewSession extends EditRecord
{
    protected static string $resource = InterviewSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
