<?php

namespace App\Filament\Resources\InterviewEvaluations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InterviewEvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('session.user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('session.cv.title')
                    ->label('CV Title')
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
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'blue' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                SelectFilter::make('grade'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
