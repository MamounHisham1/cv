<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

        // Note: We intentionally do NOT log the user in here
        // User will be created and authenticated after OTP verification

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return app(RegisterResponse::class);
    }
}
