<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSandboxAdminAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('sandbox_admin_authenticated')) {
            if ($request->expectsJson()) {
                abort(403, 'Akses administrator sandbox tidak diizinkan.');
            }

            return redirect()->guest(route('sandbox-admin.login'));
        }

        return $next($request);
    }
}
