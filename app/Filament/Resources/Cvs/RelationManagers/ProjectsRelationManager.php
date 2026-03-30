<?php

namespace App\Filament\Resources\Cvs\RelationManagers;

use App\Models\CvProject;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
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
                Select::make('architecture_type')
                    ->options(CvProject::ARCHITECTURE_TYPES),
                Textarea::make('aws_services_used')
                    ->helperText('JSON array of AWS services')
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
                BadgeColumn::make('architecture_type')
                    ->colors([
                        'info' => 'serverless',
                        'success' => 'microservices',
                        'warning' => 'monolithic',
                        'danger' => 'event-driven',
                        'gray' => 'multi-tier',
                        'purple' => 'hybrid',
                        'pink' => 'containerized',
                    ])
                    ->sortable(),
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
