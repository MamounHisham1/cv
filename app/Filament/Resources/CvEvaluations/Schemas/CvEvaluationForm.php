<?php

namespace App\Filament\Resources\CvEvaluations\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CvEvaluationForm
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
                        TextInput::make('filename')
                            ->maxLength(255),
                        TextInput::make('overall_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('grade')
                            ->maxLength(10),
                    ]),
                Textarea::make('summary')
                    ->maxLength(2000)
                    ->columnSpanFull(),
                Textarea::make('criteria')
                    ->helperText('JSON array of evaluation criteria and scores')
                    ->columnSpanFull(),
                Textarea::make('top_strengths')
                    ->helperText('JSON array of identified strengths')
                    ->columnSpanFull(),
                Textarea::make('critical_improvements')
                    ->helperText('JSON array of areas for improvement')
                    ->columnSpanFull(),
            ]);
    }
}
