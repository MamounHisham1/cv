<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update google_id if user exists but doesn't have it
                if (! $user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            } else {
                // Create new user with a random password (OAuth users don't need a password)
                // Google OAuth users bypass OTP verification since Google already verified their email
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(32)),
                    'email_verified_at' => now(),
                    'otp_verified_at' => now(),
                ]);

                // Grant credits for new Google OAuth users
                app(ReferralService::class)->processReferralOnRegistration($user, null);
            }

            // Ensure existing Google users also have OTP verified
            if ($user->google_id && ! $user->otp_verified_at) {
                $user->update(['otp_verified_at' => now()]);
            }

            Auth::login($user);

            // Skip OTP verification for Google OAuth users
            // Regular users still need to verify OTP
            if (! $user->google_id && ! $user->otp_verified_at) {
                return redirect()->route('otp.verify');
            }

            return redirect()->intended(config('fortify.home'));

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Unable to authenticate with Google. '.$e->getMessage(),
            ]);
        }
    }
}
