<?php

namespace App\Livewire;

use App\Services\CreditManager;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CreditBalanceIndicator extends Component
{
    public int $balance = 0;

    public function mount(): void
    {
        $this->loadBalance();
    }

    #[On('credits-updated')]
    public function loadBalance(): void
    {
        if (Auth::check()) {
            $this->balance = app(CreditManager::class)->getBalance(Auth::user());
        }
    }

    public function render()
    {
        return view('livewire.credit-balance-indicator');
    }
}
