<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_name' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'user_name.required' => 'El nombre de usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'g-recaptcha-response.required' => 'Por favor completa la verificación de seguridad reCAPTCHA ("No soy un robot").',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Verificación del reCAPTCHA con la API de Google
        $recaptchaToken = $this->input('g-recaptcha-response');
        $secretKey = config('services.recaptcha.secret_key');

        if (! $secretKey) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'La verificación de seguridad no está configurada. Contacta al administrador.',
            ]);
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $recaptchaToken,
                'remoteip' => $this->ip(),
            ]);
        } catch (ConnectionException $e) {
            Log::warning('No se pudo verificar reCAPTCHA.', ['exception' => $e::class]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'No pudimos verificar la protección de seguridad. Inténtalo de nuevo.',
            ]);
        }

        $data = $response->json();
        if (! $response->successful() || ! ($data['success'] ?? false)) {
            Log::warning('La verificación reCAPTCHA fue rechazada.', [
                'status' => $response->status(),
                'error_codes' => $data['error-codes'] ?? [],
            ]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'La verificación de seguridad falló. Inténtalo de nuevo.',
            ]);
        }

        $username = trim($this->input('user_name'));
        $password = (string) $this->input('password');

        // Búsqueda insensible a mayúsculas/minúsculas para máxima flexibilidad (ej. juan0211 o Juan0211)
        $user = User::whereRaw('LOWER(user_name) = ?', [strtolower($username)])->first();

        // Verificación de credenciales
        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_name' => trans('auth.failed'),
            ]);
        }

        if (! $user->status) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_name' => 'La cuenta está inactiva o pendiente de verificación.',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'user_name' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('user_name')).'|'.$this->ip());
    }
}
