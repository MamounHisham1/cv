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

class EducationsRelationManager extends RelationManager
{
    protected static string $relationship = 'educations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('institution')
                    ->required()
                    ->maxLength(255),
                TextInput::make('degree')
                    ->required()
                    ->maxLength(255),
                TextInput::make('field_of_study')
                    ->maxLength(255),
                TextInput::make('location')
                    ->maxLength(255),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                Toggle::make('is_current')
                    ->label('Currently studying here'),
                Textarea::make('description')
                    ->maxLength(2000)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('institution')
            ->columns([
                TextColumn::make('institution')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('degree')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('field_of_study')
                    ->searchable()
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
