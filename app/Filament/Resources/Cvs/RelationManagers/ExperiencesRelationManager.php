<?php

namespace App\Filament\Resources\Cvs\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExperiencesRelationManager extends RelationManager
{
    protected static string $relationship = 'experiences';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('company')
                    ->required()
                    ->maxLength(255),
                TextInput::make('location')
                    ->maxLength(255),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                Toggle::make('is_current')
                    ->label('Currently working here'),
                Textarea::make('description')
                    ->maxLength(5000)
                    ->columnSpanFull(),
                Textarea::make('technologies')
                    ->helperText('JSON array of technologies')
                    ->columnSpanFull(),
                Textarea::make('achievements')
                    ->helperText('JSON array of achievements')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date('M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date('M Y')
                    ->sortable()
                    ->placeholder('Present'),
                IconColumn::make('is_current')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
