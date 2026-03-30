<?php

namespace App\Filament\Resources\Cvs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CvForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('template_id')
                    ->options([
                        'professional-classic' => 'Professional Classic',
                        'modern-minimal' => 'Modern Minimal',
                        'aws-engineer' => 'AWS Engineer',
                        'technical' => 'Technical',
                        'creative' => 'Creative',
                    ])
                    ->required()
                    ->default('professional-classic'),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->required()
                    ->default('draft'),
                Textarea::make('summary')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
