<?php

namespace App\Filament\Resources\InterviewEvaluations\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InterviewEvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Select::make('interview_session_id')
                            ->relationship('session', fn ($query) => $query->with('user', 'cv'))
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user?->name} - {$record->cv?->title}")
                            ->searchable()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        TextInput::make('overall_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('grade')
                            ->maxLength(10),
                    ]),
                Textarea::make('summary')
                    ->maxLength(5000)
                    ->columnSpanFull(),
                Textarea::make('criteria')
                    ->helperText('JSON: criteria scores and reasons')
                    ->columnSpanFull(),
                Textarea::make('strengths')
                    ->helperText('JSON array of strengths')
                    ->columnSpanFull(),
                Textarea::make('improvements')
                    ->helperText('JSON array of improvements')
                    ->columnSpanFull(),
                Textarea::make('error_message')
                    ->label('Error')
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->columnSpanFull(),
            ]);
    }
}
