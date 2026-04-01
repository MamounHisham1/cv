<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use NotificationChannels\WebPush\PushSubscription;

class PushSubscriptionManager extends Component
{
    public function storeSubscription(): void
    {
        $this->validate([
            'endpoint' => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        if (! Auth::check()) {
            return;
        }

        PushSubscription::create([
            'subscribable_id' => Auth::id(),
            'subscribable_type' => 'App\Models\User',
            'endpoint' => request('endpoint'),
            'public_key' => request('keys.p256dh'),
            'auth_token' => request('keys.auth'),
        ]);
    }

    public function render()
    {
        return view('livewire.push-subscription-manager');
    }
}
