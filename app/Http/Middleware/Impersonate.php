<?php

namespace App\Http\Middleware;

use App\Services\ImpersonateService;
use Closure;
use Filament\Panel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Impersonate
{
    public function __construct(
        protected ImpersonateService $impersonateService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->impersonateService->isImpersonating()) {
            $impersonator = $this->impersonateService->getImpersonator();

            if (! $impersonator || ! $impersonator->canAccessPanel(app(Panel::class))) {
                $this->impersonateService->stop();

                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
