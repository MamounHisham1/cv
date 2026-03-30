<?php

namespace App\Filament\Resources\CvEvaluations;

use App\Filament\Resources\CvEvaluations\Pages\CreateCvEvaluation;
use App\Filament\Resources\CvEvaluations\Pages\EditCvEvaluation;
use App\Filament\Resources\CvEvaluations\Pages\ListCvEvaluations;
use App\Filament\Resources\CvEvaluations\Schemas\CvEvaluationForm;
use App\Filament\Resources\CvEvaluations\Tables\CvEvaluationsTable;
use App\Models\CvEvaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CvEvaluationResource extends Resource
{
    protected static ?string $model = CvEvaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'CV Management';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return CvEvaluationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CvEvaluationsTable::configure($table);
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
            'index' => ListCvEvaluations::route('/'),
            'create' => CreateCvEvaluation::route('/create'),
            'edit' => EditCvEvaluation::route('/{record}/edit'),
        ];
    }
}
