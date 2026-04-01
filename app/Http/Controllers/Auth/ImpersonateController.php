<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImpersonateService;
use Filament\Panel;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function __construct(
        protected ImpersonateService $impersonateService,
    ) {}

    public function start(Request $request, User $user)
    {
        $admin = $request->user();

        if (! $admin->canAccessPanel(app(Panel::class))) {
            abort(403);
        }

        if ($admin->id === $user->id) {
            abort(403, 'You cannot impersonate yourself.');
        }

        $this->impersonateService->start($admin, $user);

        return redirect()->route('drafts');
    }

    public function stop(Request $request)
    {
        if (! $this->impersonateService->isImpersonating()) {
            abort(403);
        }

        $this->impersonateService->stop();

        return redirect()->route('filament.admin.pages.dashboard');
    }
}
