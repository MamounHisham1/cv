<?php

namespace App\Filament\Resources\SentMails;

use App\Filament\Resources\SentMails\Pages\ListSentMails;
use App\Filament\Resources\SentMails\Pages\ViewSentMail;
use App\Filament\Resources\SentMails\Schemas\SentMailForm;
use App\Filament\Resources\SentMails\Tables\SentMailsTable;
use App\Models\SentMail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SentMailResource extends Resource
{
    protected static ?string $model = SentMail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::EnvelopeOpen;

    protected static string|UnitEnum|null $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 60;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SentMailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SentMailsTable::configure($table);
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
            'index' => ListSentMails::route('/'),
            'view' => ViewSentMail::route('/{record}'),
        ];
    }
}
