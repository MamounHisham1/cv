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

        if (! session()->get('otp_verified')) {
            return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
