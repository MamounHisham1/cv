<?php

namespace App\Filament\Resources\Cvs\RelationManagers;

use App\Models\CvSkill;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('category')
                    ->options(CvSkill::CATEGORIES)
                    ->required(),
                Select::make('level')
                    ->options(CvSkill::LEVELS)
                    ->required(),
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
                BadgeColumn::make('category')
                    ->colors([
                        'info' => 'technical',
                        'success' => 'software',
                        'warning' => 'industry',
                        'gray' => 'general',
                        'purple' => 'soft',
                    ])
                    ->sortable(),
                BadgeColumn::make('level')
                    ->colors([
                        'gray' => 'beginner',
                        'info' => 'intermediate',
                        'warning' => 'advanced',
                        'success' => 'expert',
                    ])
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
