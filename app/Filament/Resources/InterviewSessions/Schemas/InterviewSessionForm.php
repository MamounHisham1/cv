<?php

namespace App\Filament\Resources\InterviewSessions\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InterviewSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('cv_id')
                            ->relationship('cv', 'title')
                            ->searchable()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'setup' => 'Setup',
                                'active' => 'Active',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        Select::make('interview_type')
                            ->options([
                                'behavioral' => 'Behavioral',
                                'technical' => 'Technical',
                                'mixed' => 'Mixed',
                            ])
                            ->required(),
                    ]),
                Textarea::make('job_description')
                    ->label('Job Description')
                    ->maxLength(5000)
                    ->columnSpanFull()
                    ->rows(3),
                Grid::make(3)
                    ->schema([
                        TextInput::make('total_questions')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('duration_seconds')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('seconds'),
                        TextInput::make('conversation_id')
                            ->maxLength(100)
                            ->disabled(),
                    ]),
            ]);
    }
}
