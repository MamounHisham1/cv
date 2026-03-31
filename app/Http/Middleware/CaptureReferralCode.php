<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureReferralCode
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref') && $request->isMethod('GET')) {
            $code = strtoupper(trim($request->query('ref')));

            if (strlen($code) === 6 && ctype_alnum($code)) {
                $request->session()->put('ref', $code);
            }
        }

        return $next($request);
    }
}
