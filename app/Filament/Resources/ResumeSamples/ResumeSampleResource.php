<?php

namespace App\Filament\Resources\ResumeSamples;

use App\Filament\Resources\ResumeSamples\Pages\CreateResumeSample;
use App\Filament\Resources\ResumeSamples\Pages\EditResumeSample;
use App\Filament\Resources\ResumeSamples\Pages\ListResumeSamples;
use App\Filament\Resources\ResumeSamples\Schemas\ResumeSampleForm;
use App\Filament\Resources\ResumeSamples\Tables\ResumeSamplesTable;
use App\Models\ResumeSample;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ResumeSampleResource extends Resource
{
    protected static ?string $model = ResumeSample::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Data';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return ResumeSampleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResumeSamplesTable::configure($table);
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
            'index' => ListResumeSamples::route('/'),
            'create' => CreateResumeSample::route('/create'),
            'edit' => EditResumeSample::route('/{record}/edit'),
        ];
    }
}
