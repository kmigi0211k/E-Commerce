<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): Response
    {
        $response = response()->view('auth.login');

        if (app()->environment('production')) {
            $response->headers->set('X-Session-Diagnostic', implode(';', [
                'driver='.config('session.driver'),
                'secure='.((bool) config('session.secure') ? 'yes' : 'no'),
                'domain='.(config('session.domain') === null ? 'host-only' : 'custom'),
                'cookie='.($request->cookies->has(config('session.cookie')) ? 'received' : 'missing'),
            ]));
        }

        return $response;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Registrar última conexión
        $user = Auth::user();
        if ($user) {
            $user->last_login_at = now();
            $user->save();
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
