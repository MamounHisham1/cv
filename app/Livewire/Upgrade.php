<?php

namespace App\Livewire;

use App\Models\VfcashPayment;
use App\Services\VfcashService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Upgrade')]
class Upgrade extends Component
{
    public string $phone = '';

    public ?string $selectedPlan = null;

    public ?string $selectedTopup = null;

    public ?int $pendingPaymentId = null;

    public ?string $pendingPaymentNumber = null;

    public ?string $pendingStatus = null;

    public bool $showPhoneModal = false;

    public ?string $error = null;

    protected $listeners = ['checkPaymentStatus' => 'checkPaymentStatus'];

    public function selectPlan(string $plan): void
    {
        $this->selectedPlan = $plan;
        $this->selectedTopup = null;
        $this->showPhoneModal = true;
    }

    public function selectTopup(string $topup): void
    {
        $this->selectedTopup = $topup;
        $this->selectedPlan = null;
        $this->showPhoneModal = true;
    }

    public function confirmPurchase(VfcashService $vfcash): void
    {
        $this->error = null;
        $this->validate([
            'phone' => 'required|regex:/^01[0-9]{9}$/',
        ], [
            'phone.regex' => 'Enter a valid Egyptian phone number (01XXXXXXXXX).',
        ]);

        $type = $this->selectedPlan ? 'plan_upgrade' : 'credit_topup';
        $itemKey = $this->selectedPlan ?? $this->selectedTopup;

        try {
            $result = $vfcash->createPayment(Auth::user(), $type, $itemKey, $this->phone);

            $this->pendingPaymentId = $result['payment']->id;
            $this->pendingPaymentNumber = $result['payment']->payment_number;
            $this->pendingStatus = 'pending';
            $this->showPhoneModal = false;
        } catch (\Exception $e) {
            $this->error = 'Failed to create payment. Please try again.';
        }
    }

    public function checkPaymentStatus(): void
    {
        if (! $this->pendingPaymentId) {
            return;
        }

        $payment = VfcashPayment::find($this->pendingPaymentId);

        if (! $payment) {
            return;
        }

        $this->pendingStatus = $payment->status;

        if ($payment->isConfirmed()) {
            $this->dispatch('notify', message: 'Payment confirmed!', type: 'success');
            $this->reset(['pendingPaymentId', 'pendingPaymentNumber', 'pendingStatus', 'phone']);
        } elseif ($payment->isExpired() || $payment->isCancelled()) {
            $this->dispatch('notify', message: 'Payment '.$payment->status.'.', type: 'warning');
            $this->reset(['pendingPaymentId', 'pendingPaymentNumber', 'pendingStatus']);
        }
    }

    public function cancelPending(): void
    {
        $this->reset(['pendingPaymentId', 'pendingPaymentNumber', 'pendingStatus', 'selectedPlan', 'selectedTopup']);
    }

    public function closeModal(): void
    {
        $this->reset(['showPhoneModal', 'selectedPlan', 'selectedTopup', 'phone', 'error']);
    }

    public function render(): View
    {
        $currentPlan = Auth::user()->creditBalance?->plan ?? 'free';

        return view('livewire.upgrade', [
            'plans' => config('vfcash.plans'),
            'topups' => config('vfcash.topups'),
            'currentPlan' => $currentPlan,
        ]);
    }
}
