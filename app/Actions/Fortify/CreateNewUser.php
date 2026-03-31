<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        // Store registration data in session for OTP verification
        // User will be created after OTP is verified
        Session::put('pending_registration', [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'ref_code' => Session::get('ref'),
        ]);

        Session::forget('ref');

        // Return a temporary user object (not persisted) for Fortify's flow
        // This user won't have an ID, so auth()->login() won't actually log them in
        return new User([
            'name' => $input['name'],
            'email' => $input['email'],
        ]);
    }
}
