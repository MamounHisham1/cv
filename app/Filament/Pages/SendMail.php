<?php

namespace App\Filament\Pages;

use App\Mail\AdminMail;
use App\Models\SentMail;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;

class SendMail extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 50;

    public static function canAccess(): bool
    {
        return auth()->user()?->email === 'mamounprogrammer@gmail.com';
    }

    public function getBreadcrumb(): string
    {
        return 'Send Mail';
    }

    public static function getNavigationLabel(): string
    {
        return 'Send Mail';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'User Management';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendMail')
                ->label('Compose Email')
                ->icon(Heroicon::OutlinedEnvelope)
                ->form([
                    Select::make('recipientType')
                        ->label('Recipients')
                        ->options([
                            'all' => 'All Users',
                            'individual' => 'Specific User',
                        ])
                        ->required()
                        ->live()
                        ->selectablePlaceholder(false),

                    Select::make('userId')
                        ->label('User')
                        ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->visible(fn (callable $get): bool => $get('recipientType') === 'individual')
                        ->required(fn (callable $get): bool => $get('recipientType') === 'individual'),

                    TextInput::make('subject')
                        ->label('Subject')
                        ->required()
                        ->maxLength(255),

                    RichEditor::make('body')
                        ->label('Email Body')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(function (array $data): void {
                    $body = is_array($data['body']) ? ($data['body']['body'] ?? '') : $data['body'];
                    $subject = $data['subject'];
                    $mailable = new AdminMail(
                        emailSubject: $subject,
                        emailBody: $body,
                    );

                    if ($data['recipientType'] === 'all') {
                        User::chunk(100, function ($users) use ($mailable, $subject, $body) {
                            foreach ($users as $user) {
                                Mail::to($user)->send($mailable);

                                SentMail::create([
                                    'user_id' => $user->id,
                                    'recipient_email' => $user->email,
                                    'subject' => $subject,
                                    'body' => $body,
                                    'status' => SentMail::STATUS_SENT,
                                ]);
                            }
                        });
                    } else {
                        $user = User::findOrFail($data['userId']);
                        Mail::to($user)->send($mailable);

                        SentMail::create([
                            'user_id' => $user->id,
                            'recipient_email' => $user->email,
                            'subject' => $subject,
                            'body' => $body,
                            'status' => SentMail::STATUS_SENT,
                        ]);
                    }

                    Notification::make()
                        ->title('Email sent successfully')
                        ->success()
                        ->send();
                }),
        ];
    }
}
