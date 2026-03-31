<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Services\CreditManager;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->revealable(),

                Group::make([
                    Placeholder::make('credit_balance')
                        ->label('Credit Balance')
                        ->content(fn ($record) => $record ? app(CreditManager::class)->getBalance($record) : 0)
                        ->columnSpan(1),
                    Placeholder::make('credit_plan')
                        ->label('Plan')
                        ->content(fn ($record) => $record?->creditBalance?->plan ?? 'free')
                        ->columnSpan(1),
                    Actions::make([
                        Action::make('add_credits')
                            ->label('Add Credits')
                            ->icon('heroicon-o-plus-circle')
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
                        Action::make('set_credits')
                            ->label('Set Balance')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->color('warning')
                            ->schema([
                                TextInput::make('balance')
                                    ->label('New balance')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(100000),
                                TextInput::make('reason')
                                    ->label('Reason')
                                    ->placeholder('Admin adjustment')
                                    ->required(),
                            ])
                            ->action(function ($record, array $data) {
                                $creditManager = app(CreditManager::class);
                                $currentBalance = $creditManager->getBalance($record);
                                $diff = $data['balance'] - $currentBalance;

                                if ($diff > 0) {
                                    $creditManager->add($record, $diff, 'admin_adjustment', [
                                        'reason' => $data['reason'],
                                        'granted_by' => auth()->id(),
                                        'previous_balance' => $currentBalance,
                                    ]);
                                } elseif ($diff < 0) {
                                    $creditManager->deduct($record, abs($diff), 'admin_adjustment', null, [
                                        'reason' => $data['reason'],
                                        'granted_by' => auth()->id(),
                                        'previous_balance' => $currentBalance,
                                    ]);
                                }
                            })
                            ->successNotificationTitle('Balance updated'),
                    ]),
                ])
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
