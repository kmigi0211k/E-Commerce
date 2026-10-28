<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function adminIndex()
    {
        $this->ensureAdmin();

        $users = User::with('person', 'role')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
