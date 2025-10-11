<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.sign-up');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,patient,doctor,clinic_staff,donor'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create associated profile based on role
        switch ($request->role) {
            case 'patient':
                $user->patient()->create([
                    'user_id' => $user->id,
                    // Remove name and email since they're in the users table
                ]);
                break;
            case 'doctor':
                $user->doctor()->create([
                    'user_id' => $user->id,
                    // Remove name and email since they're in the users table
                ]);
                break;
        }

        // Redirect to login page after successful registration
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
}