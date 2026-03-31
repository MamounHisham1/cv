<?php

namespace App\Livewire;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Locked;
use Livewire\Component;

class OtpVerification extends Component
{
    #[Locked]
    public string $email;

    public string $otp = '';

    public bool $isVerifying = false;

    public int $resendCooldown = 0;

    public function mount(string $email): void
    {
        $this->email = $email;

        $user = auth()->user();
        if ($user && $user->otp_sent_at) {
            $secondsSinceSent = now()->diffInSeconds($user->otp_sent_at);
            if ($secondsSinceSent < 60) {
                $this->resendCooldown = 60 - (int) $secondsSinceSent;
            }
        }

        // Only send OTP if user doesn't have a valid one already
        if ($this->resendCooldown === 0 && (!$user || !$user->otp_code || $user->otp_expires_at?->isPast())) {
            $this->sendOtp();
        }
    }

    public function sendOtp(): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
            'otp_sent_at' => now(),
        ]);

        Mail::to($user->email)->send(new OtpMail(
            otp: $otpCode,
            expiresInMinutes: 10
        ));

        $this->resendCooldown = 60;
        $this->dispatch('otp-sent');
    }

    public function verify(): void
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();

        if (! $user) {
            $this->redirectRoute('login');

            return;
        }

        if ($user->otp_code !== $this->otp) {
            $this->addError('otp', 'Invalid verification code.');

            return;
        }

        if ($user->otp_expires_at?->isPast()) {
            $this->addError('otp', 'Verification code has expired. Please request a new one.');

            return;
        }

        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_verified_at' => now(),
        ]);

        session()->put('otp_verified', true);

        $this->redirectIntended(route('drafts', absolute: false));
    }

    public function decrementCooldown(): void
    {
        if ($this->resendCooldown > 0) {
            $this->resendCooldown--;
        }
    }

    public function render()
    {
        return view('livewire.otp-verification');
    }
}
