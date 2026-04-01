<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Notification Preferences')]
class NotificationPreferences extends Component
{
    public bool $emailEnabled = true;

    public bool $pushEnabled = true;

    public bool $evaluationNotifications = true;

    public bool $creditNotifications = true;

    public bool $referralNotifications = true;

    public function mount(): void
    {
        if (! Auth::check()) {
            return;
        }

        $prefs = Auth::user()->notification_preferences;
        $this->emailEnabled = $prefs['email_enabled'] ?? true;
        $this->pushEnabled = $prefs['push_enabled'] ?? true;
        $this->evaluationNotifications = $prefs['evaluation_notifications'] ?? true;
        $this->creditNotifications = $prefs['credit_notifications'] ?? true;
        $this->referralNotifications = $prefs['referral_notifications'] ?? true;
    }

    public function updated($property): void
    {
        if (! Auth::check()) {
            return;
        }

        $prefs = [
            'email_enabled' => $this->emailEnabled,
            'push_enabled' => $this->pushEnabled,
            'evaluation_notifications' => $this->evaluationNotifications,
            'credit_notifications' => $this->creditNotifications,
            'referral_notifications' => $this->referralNotifications,
        ];

        Auth::user()->update(['notification_preferences' => $prefs]);
    }

    public function enablePush(): void
    {
        $this->pushEnabled = true;
        $this->updated('pushEnabled');
    }

    public function render()
    {
        return view('livewire.notification-preferences');
    }
}
