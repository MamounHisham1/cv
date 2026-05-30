<?php

namespace App\Filament\Resources\InterviewSessions;

use App\Filament\Resources\InterviewSessions\Pages\EditInterviewSession;
use App\Filament\Resources\InterviewSessions\Pages\ListInterviewSessions;
use App\Filament\Resources\InterviewSessions\Pages\ViewInterviewSession;
use App\Filament\Resources\InterviewSessions\Schemas\InterviewSessionForm;
use App\Filament\Resources\InterviewSessions\Tables\InterviewSessionsTable;
use App\Models\InterviewSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InterviewSessionResource extends Resource
{
    protected static ?string $model = InterviewSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMicrophone;

    protected static string|UnitEnum|null $navigationGroup = 'Interview Management';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return InterviewSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InterviewSessionsTable::configure($table);
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
            'index' => ListInterviewSessions::route('/'),
            'view' => ViewInterviewSession::route('/{record}'),
            'edit' => EditInterviewSession::route('/{record}/edit'),
        ];
    }
}
