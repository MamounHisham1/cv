<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;

class RegisteredUserController extends Controller
{
    /**
     * Create a new registered user.
     */
    public function store(Request $request, CreateNewUser $creator): RegisterResponse
    {
        if (config('fortify.lowercase_usernames') && $request->has(Fortify::username())) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        // Create user (stores pending registration in session, doesn't persist)
        $creator->create($request->all());

        // Generate OTP and store in session
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $request->session()->put('otp_code', $otpCode);
        $request->session()->put('otp_expires_at', now()->addMinutes(10)->toIso8601String());
        $request->session()->put('otp_sent_at', now());

        // Queue the OTP email
        $pendingRegistration = $request->session()->get('pending_registration');
        Mail::to($pendingRegistration['email'])->queue(
            new OtpMail(
                otp: $otpCode,
                expiresInMinutes: 10
            )
        );

        // Note: We intentionally do NOT log the user in here
        // User will be created and authenticated after OTP verification

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return app(RegisterResponse::class);
    }
}
