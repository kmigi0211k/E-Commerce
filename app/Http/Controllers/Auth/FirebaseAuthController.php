<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

class FirebaseAuthController extends Controller
{
    public function __construct(private FirebaseTokenVerifier $verifier)
    {
    }

    /**
     * Registro: el navegador ya creó la cuenta en Firebase (correo + contraseña).
     * Aquí se crea el perfil en MySQL (people + users). La contraseña NUNCA llega a Laravel.
     * La sesión no se abre hasta que el correo esté verificado.
     */
    public function handleFirebaseRegister(Request $request): JsonResponse
    {
        $data = $request->validate([
            'idToken' => ['required', 'string'],
            'names' => ['required', 'string', 'max:50'],
            'user_name' => ['required', 'string', 'max:15', 'unique:users,user_name'],
        ], [
            'names.required' => 'El nombre completo es obligatorio.',
            'names.max' => 'El nombre no puede superar los 50 caracteres.',
            'user_name.required' => 'El nombre de usuario es obligatorio.',
            'user_name.max' => 'El nombre de usuario no puede superar los 15 caracteres.',
            'user_name.unique' => 'Este nombre de usuario ya está en uso.',
        ]);

        $claims = $this->claimsOrFail($data['idToken']);
        if ($claims instanceof JsonResponse) {
            return $claims;
        }

        if (Person::whereRaw('LOWER(email) = ?', [$claims['email']])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Este correo electrónico ya está registrado en la plataforma.',
            ], 422);
        }

        DB::transaction(function () use ($data, $claims) {
            $person = Person::create([
                'names' => $data['names'],
                'email' => $claims['email'],
            ]);

            $userData = [
                'user_name' => $data['user_name'],
                // Contraseña aleatoria inutilizable: la autenticación real la hace Firebase.
                'password' => Hash::make(Str::random(40)),
                'status' => 0, // se activa en el primer login con el correo verificado
                'person_id' => $person->id,
                'role_id' => 2, // Cliente
            ];

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $userData['email_verified_at'] = null;
            }

            User::create($userData);
        });

        return response()->json([
            'success' => true,
            'message' => 'Cuenta creada. Te enviamos un enlace de verificación a tu correo.',
            'redirect' => route('login'),
        ], 201);
    }

    /**
     * Login: recibe el ID token de Firebase, lo valida con Google y abre la sesión de Laravel.
     */
    public function handleFirebaseLogin(Request $request): JsonResponse
    {
        $request->validate([
            'idToken' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $claims = $this->claimsOrFail($request->input('idToken'));
        if ($claims instanceof JsonResponse) {
            return $claims;
        }

        // Sin correo verificado no se entra (evita que alguien se registre con un correo ajeno).
        // Nota: Las cuentas autenticadas con Google siempre tienen email_verified = true.
        if (! $claims['email_verified']) {
            return response()->json([
                'success' => false,
                'code' => 'email_not_verified',
                'message' => 'Debes verificar tu correo electrónico antes de ingresar a la plataforma.',
            ], 403);
        }

        $user = DB::transaction(function () use ($claims) {
            $person = Person::whereRaw('LOWER(email) = ?', [$claims['email']])->first();

            if (! $person) {
                $person = Person::create([
                    'names' => Str::limit($claims['name'] ?: explode('@', $claims['email'])[0], 50, ''),
                    'email' => $claims['email'],
                ]);
            } elseif (! empty($claims['name']) && (empty($person->names) || $person->names === explode('@', $claims['email'])[0])) {
                $person->names = Str::limit($claims['name'], 50, '');
                $person->save();
            }

            $user = User::where('person_id', $person->id)->first();

            $hasEmailVerifiedAt = Schema::hasColumn('users', 'email_verified_at');

            if (! $user) {
                $userData = [
                    'user_name' => $this->uniqueUserName($claims['email']),
                    'password' => Hash::make(Str::random(40)),
                    'status' => 1,
                    'person_id' => $person->id,
                    'role_id' => 2, // Cliente
                ];

                if ($hasEmailVerifiedAt) {
                    $userData['email_verified_at'] = now();
                }

                $user = User::create($userData);
            } else {
                $isFirstVerifiedLogin = false;
                if ($hasEmailVerifiedAt && empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $isFirstVerifiedLogin = true;
                }

                // Si era una cuenta pendiente de verificación o primer login, activarla
                if ($isFirstVerifiedLogin || (! $user->status && empty($user->last_login_at))) {
                    $user->status = 1;
                }
            }

            if (Schema::hasColumn('users', 'last_login_at')) {
                $user->last_login_at = now();
            }

            $user->save();

            return $user;
        });

        if (! $user->status) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta ha sido deshabilitada. Contacta al administrador del sistema.',
            ], 403);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Autenticación exitosa.',
            'redirect' => $request->session()->pull('url.intended', RouteServiceProvider::HOME),
            'user' => [
                'user_name' => $user->user_name,
                'email' => $claims['email'],
            ],
        ]);
    }

    /**
     * @return array{uid:string,email:string,email_verified:bool,name:?string}|JsonResponse
     */
    private function claimsOrFail(string $idToken): array|JsonResponse
    {
        try {
            $claims = $this->verifier->verify($idToken);
        } catch (RuntimeException $e) {
            Log::error('Firebase verification error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'No se pudo validar la sesión con Firebase en el servidor. Inténtalo más tarde.',
            ], 503);
        }

        if (! $claims) {
            return response()->json([
                'success' => false,
                'message' => 'La sesión de Firebase no es válida o expiró. Inténtalo de nuevo.',
            ], 401);
        }

        return $claims;
    }

    /**
     * Nombre de usuario único de máximo 15 caracteres (límite de la columna).
     */
    private function uniqueUserName(string $email): string
    {
        $base = substr(Str::slug(explode('@', $email)[0], ''), 0, 10) ?: 'usuario';
        $candidate = $base;

        while (User::where('user_name', $candidate)->exists()) {
            $candidate = substr($base, 0, 9) . random_int(100, 99999);
            $candidate = substr($candidate, 0, 15);
        }

        return $candidate;
    }
}
