<?php

namespace App\Filament\Resources\ResumeSamples\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResumeSamplesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('source')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                BadgeColumn::make('decision')
                    ->colors([
                        'success' => 'accepted',
                        'danger' => 'rejected',
                        'warning' => 'pending',
                    ])
                    ->sortable(),
                TextColumn::make('content')
                    ->limit(50)
                    ->tooltip(fn ($record): ?string => $record->content)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('decision')
                    ->options([
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'pending' => 'Pending',
                    ]),
                SelectFilter::make('source'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
