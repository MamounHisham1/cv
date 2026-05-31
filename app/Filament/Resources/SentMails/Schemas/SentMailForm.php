<?php

namespace App\Filament\Resources\SentMails\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SentMailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('recipient_email')
                    ->disabled(),
                TextInput::make('user.name')
                    ->label('User')
                    ->disabled(),
                TextInput::make('subject')
                    ->disabled(),
                TextInput::make('template')
                    ->disabled(),
                TextInput::make('status')
                    ->disabled(),
                Textarea::make('body')
                    ->disabled()
                    ->columnSpanFull()
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['body'] ?? '') : $state),
            ]);
    }
}
