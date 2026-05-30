<?php

namespace App\Filament\Resources\InterviewEvaluations\Pages;

use App\Filament\Resources\InterviewEvaluations\InterviewEvaluationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewInterviewEvaluation extends ViewRecord
{
    protected static string $resource = InterviewEvaluationResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Session Context')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('session.user.name')
                                    ->label('User'),
                                TextEntry::make('session.cv.title')
                                    ->label('CV Title'),
                                TextEntry::make('session.interview_type')
                                    ->label('Interview Type')
                                    ->badge(),
                            ]),
                        TextEntry::make('session.durationFormatted')
                            ->label('Duration'),
                    ]),
                Section::make('Evaluation Result')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('overall_score')
                                    ->numeric()
                                    ->badge()
                                    ->color(fn ($state): string => match (true) {
                                        $state >= 80 => 'success',
                                        $state >= 60 => 'warning',
                                        default => 'danger',
                                    }),
                                TextEntry::make('grade')
                                    ->badge()
                                    ->colors([
                                        'success' => ['A', 'A+', 'A-'],
                                        'warning' => ['B', 'B+', 'B-'],
                                        'danger' => ['C', 'D', 'F'],
                                    ]),
                                TextEntry::make('status')
                                    ->badge()
                                    ->colors([
                                        'warning' => 'pending',
                                        'blue' => 'processing',
                                        'success' => 'completed',
                                        'danger' => 'failed',
                                    ]),
                            ]),
                        TextEntry::make('summary')
                            ->columnSpanFull(),
                        Section::make('Criteria Scores')
                            ->visible(fn ($record): bool => filled($record->criteria))
                            ->schema([
                                RepeatableEntry::make('criteria')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('score')
                                                    ->numeric()
                                                    ->badge()
                                                    ->color(fn ($state): string => match (true) {
                                                        $state >= 8 => 'success',
                                                        $state >= 5 => 'warning',
                                                        default => 'danger',
                                                    }),
                                                TextEntry::make('reason'),
                                            ]),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('strengths')
                                    ->label('Strengths')
                                    ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : $state)
                                    ->visible(fn ($record): bool => filled($record->strengths)),
                                TextEntry::make('improvements')
                                    ->label('Areas for Improvement')
                                    ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : $state)
                                    ->visible(fn ($record): bool => filled($record->improvements)),
                            ]),
                    ]),
                Section::make('Error Details')
                    ->visible(fn ($record): bool => $record->isFailed() && filled($record->error_message))
                    ->schema([
                        TextEntry::make('error_message')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
