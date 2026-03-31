<?php

use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk()
        ->assertSee('bg-zinc-950/80', false)
        ->assertSee('backdrop-blur-xl', false)
        ->assertSee('bg-zinc-900/50', false)
        ->assertSee('focus-visible:border-emerald-500/50', false)
        ->assertSee('border-white/10', false)
        ->assertDontSee('placeholder=', false)
        ->assertDontSee('show = !show', false);
});

test('new users can register and are redirected to otp verification', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('otp.verify', absolute: false));

    // User should NOT be authenticated yet - they need to verify OTP first
    $this->assertGuest();

    // Pending registration should be stored in session
    $this->assertNotNull(session('pending_registration'));
    expect(session('pending_registration')['email'])->toBe('test@example.com');
    expect(session('pending_registration')['name'])->toBe('John Doe');
});
