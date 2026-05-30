<?php

namespace App\Filament\Resources\InterviewEvaluations;

use App\Filament\Resources\InterviewEvaluations\Pages\EditInterviewEvaluation;
use App\Filament\Resources\InterviewEvaluations\Pages\ListInterviewEvaluations;
use App\Filament\Resources\InterviewEvaluations\Pages\ViewInterviewEvaluation;
use App\Filament\Resources\InterviewEvaluations\Schemas\InterviewEvaluationForm;
use App\Filament\Resources\InterviewEvaluations\Tables\InterviewEvaluationsTable;
use App\Models\InterviewEvaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InterviewEvaluationResource extends Resource
{
    protected static ?string $model = InterviewEvaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Interview Management';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return InterviewEvaluationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InterviewEvaluationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInterviewEvaluations::route('/'),
            'view' => ViewInterviewEvaluation::route('/{record}'),
            'edit' => EditInterviewEvaluation::route('/{record}/edit'),
        ];
    }
}
