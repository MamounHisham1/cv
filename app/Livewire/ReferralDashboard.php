<?php

namespace App\Livewire;

use App\Models\Referral;
use App\Services\CreditManager;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Referrals')]
class ReferralDashboard extends Component
{
    public bool $copied = false;

    #[Computed]
    public function referralLink(): string
    {
        return app(ReferralService::class)->getReferralLink(Auth::user());
    }

    #[Computed]
    public function referralCode(): string
    {
        $code = app(ReferralService::class)->getCodeForUser(Auth::user());

        if (! $code) {
            $code = app(ReferralService::class)->generateCodeForUser(Auth::user());
        }

        return $code->code;
    }

    #[Computed]
    public function totalReferrals(): int
    {
        return Referral::where('referrer_id', Auth::id())->count();
    }

    #[Computed]
    public function creditsEarnedFromReferrals(): int
    {
        return Auth::user()->creditTransactions()
            ->whereIn('type', ['referral_signup'])
            ->sum('amount');
    }

    #[Computed]
    public function recentReferrals()
    {
        return Referral::where('referrer_id', Auth::id())
            ->with('referred')
            ->latest()
            ->take(10)
            ->get();
    }

    #[Computed]
    public function currentBalance(): int
    {
        return app(CreditManager::class)->getBalance(Auth::user());
    }

    public function copyLink(): void
    {
        $this->copied = true;
    }

    public function render()
    {
        return view('livewire.referral-dashboard');
    }
}
