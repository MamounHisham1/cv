<?php

namespace App\Filament\Resources\SentMails\Pages;

use App\Filament\Resources\SentMails\SentMailResource;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewSentMail extends ViewRecord
{
    protected static string $resource = SentMailResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Email Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('User')
                                    ->default('-'),
                                TextEntry::make('recipient_email')
                                    ->label('Recipient'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn ($state): string => match ($state) {
                                        'sent' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                        TextEntry::make('subject')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->dateTime('M j, Y g:i A'),
                    ]),
                Section::make('Body')
                    ->schema([
                        TextEntry::make('body')
                            ->html()
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => is_array($state) ? ($state['body'] ?? '') : $state),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
