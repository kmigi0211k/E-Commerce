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
            // Si el usuario necesita resetear pero intenta ir a otra página que no sea el formulario de info
            if (!$request->is('profile/info*') && !$request->is('logout')) {
                return redirect()->route('profile.info')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña antes de continuar.');
            }
        }

        return $next($request);
    }
}
