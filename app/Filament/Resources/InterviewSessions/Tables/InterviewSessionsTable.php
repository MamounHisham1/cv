<?php

namespace App\Filament\Resources\InterviewSessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InterviewSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cv.title')
                    ->label('CV Title')
                    ->searchable()
                    ->limit(30),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'active',
                        'success' => 'completed',
                        'gray' => 'setup',
                    ])
                    ->sortable(),
                BadgeColumn::make('interview_type')
                    ->colors([
                        'blue' => 'behavioral',
                        'violet' => 'technical',
                        'emerald' => 'mixed',
                    ])
                    ->sortable(),
                TextColumn::make('total_questions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('formatted_duration')
                    ->label('Duration')
                    ->sortable(query: fn ($query) => $query->orderBy('duration_seconds', 'desc')),
                TextColumn::make('messages_count')
                    ->label('Messages')
                    ->getStateUsing(fn ($record): int => $record->messages()->count())
                    ->sortable(false),
                TextColumn::make('started_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'setup' => 'Setup',
                        'active' => 'Active',
                        'completed' => 'Completed',
                    ]),
                SelectFilter::make('interview_type')
                    ->options([
                        'behavioral' => 'Behavioral',
                        'technical' => 'Technical',
                        'mixed' => 'Mixed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
    }
}
