<?php

namespace App\Filament\Resources\CvEvaluations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CvEvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('filename')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('overall_score')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                BadgeColumn::make('grade')
                    ->colors([
                        'success' => ['A', 'A+', 'A-'],
                        'warning' => ['B', 'B+', 'B-'],
                        'danger' => ['C', 'D', 'F', 'C-', 'D-', 'F-'],
                    ])
                    ->sortable(),
                TextColumn::make('summary')
                    ->limit(60)
                    ->tooltip(fn ($record): ?string => strip_tags($record->summary))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('grade'),
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
