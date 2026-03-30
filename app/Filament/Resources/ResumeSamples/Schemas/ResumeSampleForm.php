<?php

namespace App\Filament\Resources\ResumeSamples\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResumeSampleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('external_id')
                    ->maxLength(255),
                TextInput::make('role')
                    ->required()
                    ->maxLength(255),
                Select::make('source')
                    ->options([
                        'resume_dataset' => 'Resume Dataset',
                        'user_upload' => 'User Upload',
                        'scraped' => 'Scraped',
                    ])
                    ->default('resume_dataset'),
                Select::make('decision')
                    ->options([
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'pending' => 'Pending',
                    ]),
                Textarea::make('reason')
                    ->maxLength(2000),
                Textarea::make('job_description')
                    ->maxLength(5000)
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->required()
                    ->maxLength(50000)
                    ->columnSpanFull()
                    ->rows(10),
            ]);
    }
}
