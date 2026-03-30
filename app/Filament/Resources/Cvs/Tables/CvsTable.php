<?php

namespace App\Filament\Resources\Cvs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CvsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('template_id')
                    ->label('Template')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => str_replace('-', ' ', ucfirst($state))),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ])
                    ->sortable(),
                TextColumn::make('experiences_count')
                    ->label('Experiences')
                    ->counts('experiences')
                    ->sortable(),
                TextColumn::make('skills_count')
                    ->label('Skills')
                    ->counts('skills')
                    ->sortable(),
                TextColumn::make('certifications_count')
                    ->label('Certs')
                    ->counts('certifications')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                SelectFilter::make('template_id')
                    ->label('Template')
                    ->options([
                        'professional-classic' => 'Professional Classic',
                        'modern-minimal' => 'Modern Minimal',
                        'aws-engineer' => 'AWS Engineer',
                        'technical' => 'Technical',
                        'creative' => 'Creative',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
