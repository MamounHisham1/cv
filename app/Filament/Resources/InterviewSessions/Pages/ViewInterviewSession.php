<?php

namespace App\Filament\Resources\InterviewSessions\Pages;

use App\Filament\Resources\InterviewSessions\InterviewSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewInterviewSession extends ViewRecord
{
    protected static string $resource = InterviewSessionResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Session Info')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('User'),
                                TextEntry::make('cv.title')
                                    ->label('CV Title'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn ($state): string => match ($state) {
                                        'active' => 'warning',
                                        'completed' => 'success',
                                        default => 'gray',
                                    }),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('interview_type')
                                    ->label('Type')
                                    ->badge(),
                                TextEntry::make('total_questions')
                                    ->numeric(),
                                TextEntry::make('formatted_duration')
                                    ->label('Duration'),
                            ]),
                        TextEntry::make('started_at')
                            ->dateTime('M j, Y g:i A'),
                        TextEntry::make('completed_at')
                            ->dateTime('M j, Y g:i A'),
                        TextEntry::make('job_description')
                            ->label('Job Description')
                            ->visible(fn ($record): bool => filled($record->job_description))
                            ->columnSpanFull(),
                    ]),
                Section::make('Evaluation')
                    ->visible(fn ($record): bool => $record->evaluation)
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('evaluation.overall_score')
                                    ->numeric()
                                    ->label('Score')
                                    ->badge()
                                    ->color(fn ($state): string => match (true) {
                                        $state >= 80 => 'success',
                                        $state >= 60 => 'warning',
                                        default => 'danger',
                                    }),
                                TextEntry::make('evaluation.grade')
                                    ->badge()
                                    ->colors([
                                        'success' => ['A', 'A+', 'A-'],
                                        'warning' => ['B', 'B+', 'B-'],
                                        'danger' => ['C', 'D', 'F'],
                                    ]),
                                TextEntry::make('evaluation.status')
                                    ->badge()
                                    ->colors([
                                        'warning' => 'pending',
                                        'blue' => 'processing',
                                        'success' => 'completed',
                                        'danger' => 'failed',
                                    ]),
                            ]),
                        TextEntry::make('evaluation.summary')
                            ->columnSpanFull(),
                        Section::make('Criteria Scores')
                            ->visible(fn ($record): bool => filled($record->evaluation?->criteria))
                            ->schema([
                                RepeatableEntry::make('evaluation.criteria')
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
                        TextEntry::make('evaluation.strengths')
                            ->label('Strengths')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : $state)
                            ->columnSpanFull()
                            ->visible(fn ($record): bool => filled($record->evaluation?->strengths)),
                        TextEntry::make('evaluation.improvements')
                            ->label('Areas for Improvement')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : $state)
                            ->columnSpanFull()
                            ->visible(fn ($record): bool => filled($record->evaluation?->improvements)),
                    ]),
                Section::make('Transcript')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('messages')
                            ->schema([
                                TextEntry::make('role')
                                    ->badge()
                                    ->color(fn ($state): string => $state === 'interviewer' ? 'blue' : 'emerald'),
                                TextEntry::make('content')
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record): bool => $record->messages()->exists()),
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
