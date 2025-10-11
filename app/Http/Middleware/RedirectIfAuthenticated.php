<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect based on user role
                $user = Auth::user();
                
                switch ($user->role) {
                    case 'admin':
                        return redirect('/admin/index');
                    case 'doctor':
                        return redirect('/doctor/dashboard');
                    case 'clinic_staff':
                        return redirect('/clinic/dashboard');
                    case 'patient':
                        return redirect('/patient/dashboard');
                    case 'donor':
                        return redirect('/donor/dashboard');
                    default:
                        return redirect('/admin/index');
                }
            }
        }

        return $next($request);
    }
}