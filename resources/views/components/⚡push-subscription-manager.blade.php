<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public bool $isSubscribed = false;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->isSubscribed = $user->pushSubscriptions()->exists();
    }

    public function storeSubscription(Request $request): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $user->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $request->input('endpoint')],
            [
                'content_encoding' => 'aesgcm',
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
            ]
        );

        $this->isSubscribed = true;
    }

    public function unsubscribe(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $user->pushSubscriptions()->delete();
        $this->isSubscribed = false;
    }

    public function render()
    {
        return view('livewire.push-subscription-manager');
    }
};
