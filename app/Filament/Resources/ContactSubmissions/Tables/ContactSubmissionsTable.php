<?php

namespace App\Filament\Resources\ContactSubmissions\Tables;

use App\Models\ContactSubmission;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactSubmissionsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->query(ContactSubmission::query()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(60),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('read_at')
                    ->label('Read')
                    ->dateTime()
                    ->placeholder('Unread')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()
                    ->modalHeading('Contact Submission')
                    ->form(fn (ContactSubmission $record) => [
                        TextInput::make('name')
                            ->default($record->name)
                            ->disabled(),
                        TextInput::make('email')
                            ->default($record->email)
                            ->disabled(),
                        TextInput::make('subject')
                            ->default($record->subject)
                            ->disabled(),
                        Textarea::make('message')
                            ->default($record->message)
                            ->disabled()
                            ->rows(8),
                    ])
                    ->after(function (ContactSubmission $record) {
                        if (! $record->read_at) {
                            $record->update(['read_at' => now()]);
                        }
                    }),
            ]);
    }
}
