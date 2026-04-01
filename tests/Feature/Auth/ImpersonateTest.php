<?php

use App\Models\User;
use App\Services\ImpersonateService;
use Illuminate\Support\Facades\Session;

test('admin can impersonate a user', function () {
    $admin = User::factory()->create([
        'email' => 'mamounprogrammer@gmail.com',
        'otp_verified_at' => now(),
    ]);
    $target = User::factory()->create([
        'otp_verified_at' => now(),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('impersonate.start', $target));

    $response->assertRedirect(route('drafts'));
    $this->assertAuthenticatedAs($target);
    expect(Session::get('impersonator_id'))->toBe($admin->id);
});

test('non-admin cannot impersonate a user', function () {
    $user = User::factory()->create([
        'otp_verified_at' => now(),
    ]);
    $target = User::factory()->create([
        'otp_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->get(route('impersonate.start', $target));

    $response->assertForbidden();
    expect(Session::get('impersonator_id'))->toBeNull();
});

test('admin cannot impersonate themselves', function () {
    $admin = User::factory()->create([
        'email' => 'mamounprogrammer@gmail.com',
        'otp_verified_at' => now(),
    ]);

    $response = $this->actingAs($admin)
        ->get(route('impersonate.start', $admin));

    $response->assertForbidden();
    expect(Session::get('impersonator_id'))->toBeNull();
});

test('impersonating user can stop impersonation', function () {
    $admin = User::factory()->create([
        'email' => 'mamounprogrammer@gmail.com',
        'otp_verified_at' => now(),
    ]);
    $target = User::factory()->create([
        'otp_verified_at' => now(),
    ]);

    $impersonateService = app(ImpersonateService::class);
    $impersonateService->start($admin, $target);

    $this->assertAuthenticatedAs($target);

    $response = $this->post(route('impersonate.stop'));

    $response->assertRedirect();
    $this->assertAuthenticatedAs($admin);
    expect($impersonateService->isImpersonating())->toBeFalse();
});

test('non-impersonating user cannot stop impersonation', function () {
    $user = User::factory()->create([
        'otp_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->post(route('impersonate.stop'));

    $response->assertForbidden();
});

test('impersonation bypasses otp verification', function () {
    $admin = User::factory()->create([
        'email' => 'mamounprogrammer@gmail.com',
        'otp_verified_at' => now(),
    ]);
    $target = User::factory()->create([
        'otp_verified_at' => null,
    ]);

    $impersonateService = app(ImpersonateService::class);
    $impersonateService->start($admin, $target);

    $response = $this->get(route('cv.builder'));

    $response->assertSuccessful();
});

test('impersonate service detects impersonation state', function () {
    $admin = User::factory()->create([
        'email' => 'mamounprogrammer@gmail.com',
    ]);
    $target = User::factory()->create();

    $impersonateService = app(ImpersonateService::class);

    expect($impersonateService->isImpersonating())->toBeFalse();
    expect($impersonateService->getImpersonator())->toBeNull();

    $impersonateService->start($admin, $target);

    expect($impersonateService->isImpersonating())->toBeTrue();
    expect($impersonateService->getImpersonator()->id)->toBe($admin->id);
    expect($impersonateService->getRealUser()->id)->toBe($admin->id);
});

test('impersonation is cleared when impersonator cannot access panel', function () {
    $admin = User::factory()->create([
        'email' => 'mamounprogrammer@gmail.com',
        'otp_verified_at' => now(),
    ]);
    $target = User::factory()->create([
        'otp_verified_at' => now(),
    ]);

    $impersonateService = app(ImpersonateService::class);
    $impersonateService->start($admin, $target);

    $admin->update(['email' => 'not-admin@example.com']);

    $response = $this->get(route('drafts'));

    $response->assertRedirect(route('login'));
});
