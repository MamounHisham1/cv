<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Skip OTP verification for Google OAuth users
        if ($user->google_id) {
            return $next($request);
        }

        // Check if user has verified OTP (check database, not session)
        if (! $user->otp_verified_at) {
            // Log out the user if they haven't verified OTP
            // This handles edge case where user exists but didn't complete verification
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Please complete email verification before accessing the site.',
            ]);
        }

        return $next($request);
    }
}
