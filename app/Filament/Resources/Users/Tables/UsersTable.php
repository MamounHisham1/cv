<?php

namespace App\Filament\Resources\Users\Tables;

use App\Services\CreditManager;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('User')
                    ->state(fn ($record) => "{$record->name} — {$record->email}")
                    ->searchable(['name', 'email'])
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('two_factor_confirmed_at')
                    ->label('2FA')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('cvs_count')
                    ->label('CVs')
                    ->counts('cvs')
                    ->sortable(),
                TextColumn::make('credit_balance')
                    ->label('Credits')
                    ->state(fn ($record) => $record->creditBalance?->balance ?? 0)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email verified')
                    ->nullable(),
                TernaryFilter::make('two_factor_confirmed_at')
                    ->label('Two-factor enabled')
                    ->nullable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('add_credits')
                    ->label('Add Credits')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->schema([
                        TextInput::make('amount')
                            ->label('Credits to add')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(10000),
                        TextInput::make('reason')
                            ->label('Reason')
                            ->placeholder('Admin grant — promotional bonus')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        app(CreditManager::class)->add($record, $data['amount'], 'admin_grant', [
                            'reason' => $data['reason'],
                            'granted_by' => auth()->id(),
                        ]);
                    })
                    ->successNotificationTitle('Credits added successfully'),
                Action::make('impersonate')
                    ->label('Impersonate')
                    ->icon('heroicon-o-user')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->id === auth()->id())
                    ->action(function ($record) {
                        return redirect()->route('impersonate.start', $record);
                    })
                    ->visible(fn () => auth()->user()->canAccessPanel(filament()->getCurrentPanel())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
