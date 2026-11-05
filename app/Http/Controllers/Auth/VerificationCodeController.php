<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class VerificationCodeController extends Controller
{
    /**
     * Muestra la pantalla para ingresar el código de verificación OTP.
     */
    public function notice(Request $request): View|RedirectResponse
    {
        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('person')->find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.verify-code', [
            'user' => $user,
            'email' => $user->person?->email ?? session('verification_email'),
        ]);
    }

    /**
     * Valida el código de 6 dígitos ingresado por el usuario.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'code.required' => 'Debes ingresar el código de verificación.',
            'code.regex' => 'El código debe tener exactamente 6 dígitos.',
        ]);

        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'La sesión de verificación ha expirado. Por favor inicia sesión.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $codeIsValid = $user->verification_code
            && $user->verification_expires_at
            && now()->lt($user->verification_expires_at)
            && hash_equals((string) $user->verification_code, $request->code);

        if (!$codeIsValid) {
            return back()->withInput()->with('error', 'El código de verificación es incorrecto o ha expirado. Solicita uno nuevo.');
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_expires_at = null;
        $user->status = 1;
        $user->save();

        session()->forget(['verification_user_id', 'verification_email']);

        // Iniciar sesión
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('success', '¡Cuenta verificada exitosamente! Bienvenido a ProductosPro.');
    }

    /**
     * Reenvía un nuevo código de 6 dígitos al correo.
     */
    public function resend(Request $request): RedirectResponse
    {
        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::with('person')->find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $email = $user->person?->email ?? session('verification_email');
        if (!$email) {
            return back()->with('error', 'No se encontró el correo asociado a esta cuenta.');
        }

        if (!Schema::hasColumn('users', 'verification_code')
            || !Schema::hasColumn('users', 'verification_expires_at')) {
            Log::error('No se puede reenviar el código porque faltan columnas de verificación de correo.');

            return back()->with('error', 'No fue posible reenviar el código. Inténtalo más tarde.');
        }

        try {
            DB::transaction(function () use ($user, $email) {
                $newCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $user->verification_code = $newCode;
                $user->verification_expires_at = now()->addMinutes(15);
                $user->save();

                Mail::to($email)->send(new VerificationCodeMail($newCode, $user->user_name));
            });
        } catch (\Throwable $e) {
            Log::warning('No se pudo reenviar el código de verificación.', [
                'user_id' => $user->id,
                'exception' => get_class($e),
            ]);

            return back()->with('error', 'No pudimos enviar el código. Inténtalo de nuevo más tarde.');
        }

        return back()->with('success', 'Se ha enviado un nuevo código de 6 dígitos a tu correo electrónico.');
    }
}
