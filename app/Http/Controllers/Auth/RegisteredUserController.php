<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'names' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:people'],
            'user_name' => ['required', 'string', 'max:15', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'names.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'user_name.required' => 'El nombre de usuario es obligatorio.',
            'user_name.unique' => 'Este nombre de usuario ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener letras.',
            'password.mixed' => 'La contraseña debe incluir al menos una letra mayúscula y una minúscula.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.symbols' => 'La contraseña debe incluir al menos un símbolo o carácter especial (@, $, !, %, *, #, ?, &).',
        ]);

        foreach (['verification_code', 'verification_expires_at', 'email_verified_at'] as $column) {
            if (!Schema::hasColumn('users', $column)) {
                Log::error('No se puede registrar un usuario porque faltan columnas de verificación de correo.');

                return back()
                    ->withInput($request->except(['password', 'password_confirmation']))
                    ->withErrors(['email' => 'El registro no está disponible temporalmente. Inténtalo más tarde.']);
            }
        }

        try {
            $user = DB::transaction(function () use ($request) {
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $person = \App\Models\Person::create([
                    'names' => $request->names,
                    'email' => $request->email,
                ]);

                $user = User::create([
                    'user_name' => $request->user_name,
                    'password' => Hash::make($request->password),
                    'status' => 0,
                    'person_id' => $person->id,
                    'role_id' => 2,
                    'verification_code' => $code,
                    'verification_expires_at' => now()->addMinutes(15),
                    'email_verified_at' => null,
                ]);

                event(new Registered($user));
                Mail::to($request->email)->send(new VerificationCodeMail($code, $request->user_name));

                return $user;
            });
        } catch (\Throwable $e) {
            Log::warning('No se pudo completar el registro ni enviar el código de verificación.', [
                'exception' => get_class($e),
            ]);

            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['email' => 'No pudimos enviar el código de verificación. Revisa tu correo e inténtalo de nuevo.']);
        }

        session([
            'verification_user_id' => $user->id,
            'verification_email' => $request->email,
        ]);

        return redirect()->route('verification.code.notice')->with('success', '¡Registro casi listo! Te hemos enviado un código de 6 dígitos a tu correo.');
    }
}