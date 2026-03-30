<?php

namespace App\Filament\Widgets;

use App\Models\CvEvaluation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentCvEvaluations extends TableWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Recent CV Evaluations';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CvEvaluation::query()->with('user')->latest()->limit(10)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('filename')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('overall_score')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('grade')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'A', 'A+', 'A-' => 'success',
                        'B', 'B+', 'B-' => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('summary')
                    ->limit(50)
                    ->html()
                    ->tooltip(fn ($record): ?string => strip_tags($record->summary)),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
