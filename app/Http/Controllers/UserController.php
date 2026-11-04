<?php

namespace App\Http\Controllers;

use App\Models\Role;
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

    public function promoteToAdmin(User $user): RedirectResponse
    {
        $this->ensureAdmin();

        if ($user->isAdmin()) {
            return redirect()->route('admin.users')->with('error', 'Este usuario ya tiene el rol de administrador.');
        }

        $adminRole = Role::firstOrCreate(
            ['description' => 'Admin'],
            ['status' => true]
        );

        $user->forceFill(['role_id' => $adminRole->id])->save();

        return redirect()->route('admin.users')
            ->with('success', 'Se asignó el rol de administrador a '.$user->user_name.'.');
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
