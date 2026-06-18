<?php

namespace App\Filament\Resources\Cvs\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'certifications';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('issuing_organization')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('issue_date')
                    ->required(),
                DatePicker::make('expiration_date'),
                TextInput::make('credential_id')
                    ->maxLength(255),
                TextInput::make('credential_url')
                    ->url()
                    ->maxLength(255),
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
                TextColumn::make('issuing_organization')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('issue_date')
                    ->date('M Y')
                    ->sortable(),
                TextColumn::make('expiration_date')
                    ->date('M Y')
                    ->sortable(),
                IconColumn::make('is_valid')
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
