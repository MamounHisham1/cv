<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;

class ImpersonateService
{
    public function __construct(
        protected AuthManager $auth,
        protected SessionManager $session,
    ) {}

    public function start(User $admin, User $target): void
    {
        $this->session->put([
            'impersonator_id' => $admin->id,
            'impersonate_guard' => $this->auth->getDefaultDriver(),
        ]);

        $this->auth->login($target);
    }

    public function stop(): void
    {
        $adminId = $this->session->pull('impersonator_id');

        if (! $adminId) {
            return;
        }

        $this->session->forget('impersonate_guard');

        $admin = User::find($adminId);

        if ($admin) {
            $this->auth->login($admin);
        }
    }

    public function isImpersonating(): bool
    {
        return $this->session->has('impersonator_id');
    }

    public function getImpersonator(): ?User
    {
        $adminId = $this->session->get('impersonator_id');

        if (! $adminId) {
            return null;
        }

        return User::find($adminId);
    }

    public function getRealUser(): ?User
    {
        return $this->getImpersonator() ?? $this->auth->user();
    }
}
