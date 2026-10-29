<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'logout',
    ];

    protected function tokensMatch($request)
    {
        $matches = parent::tokensMatch($request);

        if (! $matches && $request->isMethod('POST') && $request->is('login')) {
            $diagnostics = [
                'session_driver' => config('session.driver'),
                'session_cookie_present' => $request->cookies->has(config('session.cookie')),
                'submitted_token_present' => is_string($request->input('_token')),
                'session_token_present' => $request->hasSession() && is_string($request->session()->token()),
            ];

            Log::warning('CSRF token mismatch on login.', $diagnostics);
            error_log('CSRF token mismatch on login: '.json_encode($diagnostics));
        }

        return $matches;
    }
}
