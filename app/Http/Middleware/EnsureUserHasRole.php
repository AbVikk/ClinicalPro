<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (! $request->user()) {
            return redirect('login');
        }

        // Check if user has one of the required roles
        if (! in_array($request->user()->role, $roles)) {
            // Redirect to appropriate dashboard based on user role
            switch ($request->user()->role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'doctor':
                    return redirect('/doctor/dashboard');
                case 'clinic_staff':
                    return redirect('/clinic/dashboard');
                case 'patient':
                    return redirect('/patient/dashboard');
                case 'donor':
                    return redirect('/donor/dashboard');
                case 'primary_pharmacist':
                case 'senior_pharmacist':
                case 'clinic_pharmacist':
                    return redirect('/pharmacy/dashboard');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
}