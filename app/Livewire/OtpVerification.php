<?php

namespace App\Livewire;

use App\Mail\OtpMail;
use App\Models\User;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Livewire\Component;

class OtpVerification extends Component
{
    #[Locked]
    public string $email;

    public string $otp = '';

    public bool $isVerifying = false;

    public int $resendCooldown = 0;

    public function mount(): void
    {
        // Check for pending registration in session
        $pendingRegistration = Session::get('pending_registration');

        if (! $pendingRegistration) {
            // No pending registration - redirect to login
            $this->redirectRoute('login');

            return;
        }

        $this->email = $pendingRegistration['email'];

        // Get OTP cooldown from session
        $otpSentAt = Session::get('otp_sent_at');
        if ($otpSentAt) {
            $sentAt = Carbon::parse($otpSentAt);
            $secondsSinceSent = $sentAt->diffInSeconds(now(), false);
            if ($secondsSinceSent >= 0 && $secondsSinceSent < 60) {
                $this->resendCooldown = 60 - (int) $secondsSinceSent;
            }
        }
    }

    public function sendOtp(): void
    {
        $pendingRegistration = Session::get('pending_registration');

        if (! $pendingRegistration) {
            return;
        }

        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in session
        Session::put('otp_code', $otpCode);
        Session::put('otp_expires_at', now()->addMinutes(10)->toIso8601String());
        Session::put('otp_sent_at', now());

        Mail::to($pendingRegistration['email'])->queue(new OtpMail(
            otp: $otpCode,
            expiresInMinutes: 10
        ));

        $this->resendCooldown = 60;
        $this->dispatch('otp-sent');
    }

    public function verify(RateLimiter $limiter): void
    {
        // Rate-limit brute-force attempts against the 6-digit code.
        // Keyed by pending email + IP, plus a per-IP global cap.
        $email = Session::get('pending_registration.email', 'guest');
        $emailKey = 'otp:'.$email.'|'.request()->ip();
        $ipKey = 'otp-ip:'.request()->ip();

        if ($limiter->tooManyAttempts($emailKey, 5) || $limiter->tooManyAttempts($ipKey, 15)) {
            $seconds = max(
                $limiter->availableIn($emailKey),
                $limiter->availableIn($ipKey),
            );

            $this->addError('otp', 'Too many attempts. Try again in '.ceil($seconds / 60).' minute(s).');

            return;
        }

        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $pendingRegistration = Session::get('pending_registration');

        if (! $pendingRegistration) {
            $this->redirectRoute('login');

            return;
        }

        $storedOtp = Session::get('otp_code');
        $otpExpiresAt = Session::get('otp_expires_at');

        if ($storedOtp !== $this->otp) {
            $limiter->hit($emailKey, 600);   // 10-minute decay window
            $limiter->hit($ipKey, 60);

            $this->addError('otp', 'Invalid verification code.');

            return;
        }

        if ($otpExpiresAt && now()->isAfter($otpExpiresAt)) {
            $this->addError('otp', 'Verification code has expired. Please request a new one.');

            return;
        }

        // Successful verification — clear any throttling state for this email.
        $limiter->clear($emailKey);
        $limiter->clear($ipKey);

        // Create the user now that OTP is verified
        // OTP verification also serves as email verification
        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'password' => $pendingRegistration['password'],
            'email_verified_at' => now(),
            'otp_verified_at' => now(),
        ]);

        // Process referral and grant credits
        app(ReferralService::class)->processReferralOnRegistration(
            $user,
            $pendingRegistration['ref_code']
        );

        // Clear pending registration data from session
        Session::forget(['pending_registration', 'otp_code', 'otp_expires_at', 'otp_sent_at']);

        // Log the user in
        Auth::login($user);

        // Mark OTP as verified in session
        session()->put('otp_verified', true);

        $this->redirectIntended(route('drafts', absolute: false));
    }

    public function decrementCooldown(): void
    {
        if ($this->resendCooldown > 0) {
            $this->resendCooldown--;
        }
    }

    public function cancel(): void
    {
        Session::forget(['pending_registration', 'otp_code', 'otp_expires_at', 'otp_sent_at']);
        $this->redirectRoute('login');
    }

    public function render()
    {
        return view('livewire.otp-verification');
    }
}
