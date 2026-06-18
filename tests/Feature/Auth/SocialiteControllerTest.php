<?php

use App\Models\User;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\One\User as SocialiteUser;
use Laravel\Socialite\SocialiteManager;

beforeEach(function () {
    // Provide a real Provider fake so Socialite::driver('google')->user()
    // returns the configured Google user without hitting the network.
    $socialiteUser = mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('google-id-123');
    $socialiteUser->shouldReceive('getName')->andReturn('Jane OAuth');
    $socialiteUser->shouldReceive('getEmail')->andReturn('jane.oauth@example.com');
    $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.png');
    $this->socialiteUser = $socialiteUser;

    $provider = mock(Provider::class);
    $provider->shouldReceive('redirect')->andReturn(redirect('https://accounts.google.com/oauth'));
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    // The Socialite facade resolves Laravel\Socialite\Contracts\Factory —
    // override the whole manager so driver('google') returns our fake.
    $manager = mock(SocialiteManager::class);
    $manager->shouldReceive('driver')->with('google')->andReturn($provider);
    app()->instance(Factory::class, $manager);
});

it('redirects to Google for authentication', function () {
    $this->get(route('auth.google.redirect'))
        ->assertRedirect();
});

it('creates a brand-new user with OTP bypassed on first Google login', function () {
    $this->assertDatabaseMissing(User::class, ['email' => 'jane.oauth@example.com']);

    $response = $this->get(route('auth.google.callback'));

    $user = User::where('email', 'jane.oauth@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->google_id)->toBe('google-id-123')
        ->and($user->otp_verified_at)->not->toBeNull()
        ->and($user->email_verified_at)->not->toBeNull();

    // New Google users land on the Fortify home (dashboard), not the OTP page.
    $response->assertRedirect(config('fortify.home'));
});

it('links an existing password-registered user when the email matches', function () {
    $existing = User::factory()->create([
        'email' => 'jane.oauth@example.com',
        'google_id' => null,
    ]);

    $this->get(route('auth.google.callback'));

    expect($existing->fresh()->google_id)->toBe('google-id-123')
        ->and(User::where('email', 'jane.oauth@example.com')->count())->toBe(1);
});

it('logs in an existing Google user without creating a duplicate', function () {
    User::factory()->create([
        'email' => 'jane.oauth@example.com',
        'google_id' => 'google-id-123',
    ]);

    $this->get(route('auth.google.callback'));

    expect(User::where('email', 'jane.oauth@example.com')->count())->toBe(1);
});

it('also creates a credit balance for the brand-new user', function () {
    $this->get(route('auth.google.callback'));

    $user = User::where('email', 'jane.oauth@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->creditBalance)->not->toBeNull();
});
