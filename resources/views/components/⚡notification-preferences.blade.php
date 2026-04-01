<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public bool $emailEnabled = true;

    public bool $pushEnabled = true;

    public bool $evaluationNotifications = true;

    public bool $creditNotifications = true;

    public bool $referralNotifications = true;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->emailEnabled = $user->getNotificationPreference('email_enabled', true);
        $this->pushEnabled = $user->getNotificationPreference('push_enabled', true);
        $this->evaluationNotifications = $user->getNotificationPreference('evaluation_notifications', true);
        $this->creditNotifications = $user->getNotificationPreference('credit_notifications', true);
        $this->referralNotifications = $user->getNotificationPreference('referral_notifications', true);
    }

    public function updated(string $property): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $value = $this->$property;

        $preferenceMap = [
            'emailEnabled' => 'email_enabled',
            'pushEnabled' => 'push_enabled',
            'evaluationNotifications' => 'evaluation_notifications',
            'creditNotifications' => 'credit_notifications',
            'referralNotifications' => 'referral_notifications',
        ];

        if (isset($preferenceMap[$property])) {
            $user->updateNotificationPreference($preferenceMap[$property], $value);
        }
    }

    public function render()
    {
        return view('livewire.notification-preferences');
    }
};
