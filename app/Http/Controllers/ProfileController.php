<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display personal information form.
     */
    public function info(Request $request): View
    {
        return view('profile.info', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update personal information.
     */
    public function updateInfo(Request $request): RedirectResponse
    {
        $user = $request->user();
        $person = $user->person;

        $request->validate([
            'user_name' => 'required|string|max:15|unique:users,user_name,' . $user->id,
            'names' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update User
        $user->user_name = $request->user_name;
        
        // Update Password if provided
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $user->save();

        // Update Person
        if ($person) {
            $person->update([
                'names' => $request->names,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        return Redirect::route('profile.edit')->with('success', 'Información actualizada correctamente.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
