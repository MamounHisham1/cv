<?php

namespace App\Filament\Widgets;

use App\Models\ResumeSample;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TopResumeSamples extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Recent Resume Samples';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ResumeSample::query()->latest()->limit(10)
            )
            ->columns([
                TextColumn::make('role')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('source')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('decision')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('reason')
                    ->limit(60)
                    ->tooltip(fn ($record): ?string => $record->reason),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
