<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function adminIndex()
    {
        $this->ensureAdmin();

        $users = User::with('person', 'role')->orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin();

        $temporaryPassword = Str::random(32);
        $user->forceFill([
            'password' => Hash::make($temporaryPassword),
            'password_needs_reset' => true,
            'remember_token' => Str::random(60),
        ])->save();

        return redirect()->route('admin.users')
            ->with('success', 'Se generó una contraseña temporal. Entrégala al usuario por un canal seguro; deberá cambiarla al iniciar sesión.')
            ->with('temporary_password', $temporaryPassword);
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
