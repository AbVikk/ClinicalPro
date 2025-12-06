<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHospitalContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated, ensure they have a hospital context
        if (Auth::check()) {
            $user = Auth::user();
            
            // If user doesn't have a hospital_id, redirect to appropriate setup
            if (!$user->hospital_id) {
                // For super admins with no hospital, redirect to hospital management
                if ($user->role === 'super_admin') {
                    return redirect()->route('super_admin.hospitals.index');
                }
                
                // For regular users with no hospital, logout and show error
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is not associated with any hospital. Please contact support.');
            }
            
            // Set the hospital context in the request for downstream use
            $request->attributes->set('hospital_id', $user->hospital_id);
        }

        return $next($request);
    }
}