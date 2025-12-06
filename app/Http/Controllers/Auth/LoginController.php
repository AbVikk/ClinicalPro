<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.sign-in');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->onlyInput('email');
        }

        // Check if password is correct
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->onlyInput('email');
        }

        // Attempt to log in the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/index');
                case 'doctor':
                    return redirect('/doctor/dashboard');
                case 'nurse':
                    return redirect('/nurse/dashboard');
                case 'patient':
                    return redirect('/patient/dashboard');
                case 'donor':
                    return redirect('/donor/dashboard');
                case 'primary_pharmacist':
                    return redirect('/primary_pharmacist/dashboard');
                case 'senior_pharmacist':
                    return redirect('/senior_pharmacist/dashboard');
                case 'clinic_pharmacist':
                    // Redirect clinic pharmacist directly to POS dashboard
                    return redirect('/clinic_pharmacist/sales');
                default:
                    return redirect('/admin/index');
            }
        }

        return back()->withErrors([
            'email' => 'Unable to log you in. Please try again.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('logout.page');
    }
}