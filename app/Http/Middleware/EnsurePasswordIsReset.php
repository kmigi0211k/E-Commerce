<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsReset
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->password_needs_reset) {
            if (! $request->routeIs('profile.edit', 'password.update', 'logout')) {
                if ($request->session()->has('temporary_password')) {
                    $request->session()->keep('temporary_password');
                }

                return redirect()->route('profile.edit')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña antes de continuar.');
            }
        }

        return $next($request);
    }
}
