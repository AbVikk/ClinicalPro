<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hospital;
use Symfony\Component\HttpFoundation\Response;

class HospitalSubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the host from the request
        $host = $request->getHost();
        
        // Extract subdomain (assuming format: subdomain.domain.com)
        $parts = explode('.', $host);
        
        // If we have at least 2 parts (subdomain and domain)
        if (count($parts) >= 2) {
            $subdomain = $parts[0];
            
            // Check if this subdomain corresponds to a hospital
            $hospital = Hospital::where('domain_prefix', $subdomain)->first();
            
            if ($hospital) {
                // Set hospital context in request
                $request->attributes->set('hospital', $hospital);
                $request->attributes->set('hospital_id', $hospital->id);
                
                // If user is authenticated, ensure they belong to this hospital
                if (Auth::check() && Auth::user()->hospital_id !== $hospital->id) {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'You do not have access to this hospital.');
                }
                
                // Share hospital info with views
                view()->share('current_hospital', $hospital);
            } else {
                // If subdomain doesn't match any hospital, redirect to main site
                if ($subdomain !== config('app.domain_prefix', 'www')) {
                    return redirect(config('app.url'));
                }
            }
        }

        return $next($request);
    }
}