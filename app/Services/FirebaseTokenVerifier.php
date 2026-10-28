<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Valida un ID token de Firebase Auth contra los servidores de Google Identity Toolkit.
 *
 * El navegador NUNCA es de fiar: el correo que envía puede ser alterado.
 * Por eso el backend valida con Google (accounts:lookup) que el token sea auténtico,
 * vigente y emitido por el proyecto correspondiente.
 */
class FirebaseTokenVerifier
{
    /**
     * @return array{uid:string,email:string,email_verified:bool,name:?string}|null
     *         null si el token es inválido, expiró o la cuenta está deshabilitada.
     *
     * @throws RuntimeException si falta configuración o no hay conexión con Google.
     */
    public function verify(string $idToken): ?array
    {
        $apiKey = config('services.firebase.api_key');

        if (! $apiKey) {
            throw new RuntimeException('Falta la variable FIREBASE_API_KEY en el archivo .env');
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post('https://identitytoolkit.googleapis.com/v1/accounts:lookup?key='.$apiKey, [
                    'idToken' => $idToken,
                ]);
        } catch (ConnectionException $e) {
            throw new RuntimeException('No se pudo establecer conexión con Firebase: '.$e->getMessage());
        }

        if (! $response->successful()) {
            $errorMsg = $response->json('error.message') ?? $response->body();
            Log::warning('Firebase Token Verification failed: '.$errorMsg);
            return null;
        }

        $user = $response->json('users.0');

        if (! is_array($user) || empty($user['email'])) {
            Log::warning('Firebase Token Verification: payload de usuario vacío o sin email');
            return null;
        }

        if (! empty($user['disabled'])) {
            Log::warning('Firebase Token Verification: usuario deshabilitado en Firebase (UID: '.($user['localId'] ?? 'N/A').')');
            return null;
        }

        return [
            'uid' => (string) $user['localId'],
            'email' => strtolower(trim($user['email'])),
            'email_verified' => (bool) ($user['emailVerified'] ?? false),
            'name' => $user['displayName'] ?? null,
        ];
    }
}
