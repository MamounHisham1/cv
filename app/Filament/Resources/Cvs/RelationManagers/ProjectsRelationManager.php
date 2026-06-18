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
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(5000)
                    ->columnSpanFull(),
                Textarea::make('key_achievements')
                    ->helperText('JSON array of key achievements')
                    ->columnSpanFull(),
                TextInput::make('project_url')
                    ->url()
                    ->maxLength(255),
                TextInput::make('github_url')
                    ->url()
                    ->maxLength(255),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('start_date')
                    ->date('M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date('M Y')
                    ->sortable(),
                TextColumn::make('github_url')
                    ->label('GitHub')
                    ->url(fn ($record): ?string => $record->github_url)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),
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
