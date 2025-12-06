<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'clinic_pharmacist' => \App\Http\Middleware\ClinicPharmacistMiddleware::class,
            'senior_pharmacist' => \App\Http\Middleware\SeniorPharmacistMiddleware::class,
            'primary_pharmacist' => \App\Http\Middleware\PrimaryPharmacistMiddleware::class,
            'hospital.context' => \App\Http\Middleware\EnsureHospitalContext::class,
            'hospital.subdomain' => \App\Http\Middleware\HospitalSubdomainMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();