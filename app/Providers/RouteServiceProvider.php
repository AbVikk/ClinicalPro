<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            // ✅ API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // ✅ Public Web Routes
            Route::middleware(['web', 'hospital.subdomain'])
                ->group(base_path('routes/web.php'));

            // ✅ Admin Routes
            Route::middleware(['web', 'auth', 'role:admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // ✅ HOD Routes
            Route::middleware(['web', 'auth', 'role:hod'])
                ->prefix('hod')
                ->name('hod.')
                ->group(base_path('routes/hod.php'));

            // ✅ Doctor Routes
            Route::middleware(['web', 'auth', 'role:doctor'])
                ->prefix('doctor')
                ->name('doctor.')
                ->group(base_path('routes/doctor.php'));

            // ✅ Patient Routes
            Route::middleware(['web', 'auth', 'role:patient'])
                ->prefix('patient')
                ->name('patient.')
                ->group(base_path('routes/patient.php'));

            // ✅ Primary Pharmacist Routes
            Route::middleware(['web', 'auth', 'role:primary_pharmacist'])
                ->prefix('primary_pharmacist')
                ->name('primary_pharmacist.')
                ->group(base_path('routes/primary_pharmacist.php'));

            // ✅ Senior Pharmacist Routes
            Route::middleware(['web', 'auth', 'role:senior_pharmacist'])
                ->prefix('senior_pharmacist')
                ->name('senior_pharmacist.')
                ->group(base_path('routes/senior_pharmacist.php'));

            // ✅ Clinic Pharmacist Routes
            Route::middleware(['web', 'auth', 'role:clinic_pharmacist'])
                ->prefix('clinic_pharmacist')
                ->name('clinic_pharmacist.')
                ->group(base_path('routes/clinic_pharmacist.php'));

            // ✅ Clinic Routes
            Route::middleware(['web', 'auth', 'role:nurse'])
                ->prefix('nurse')
                ->name('nurse.')
                ->group(base_path('routes/nurse.php'));

            // ✅ Donor Routes
            Route::middleware(['web', 'auth', 'role:donor'])
                ->prefix('donor')
                ->name('donor.')
                ->group(base_path('routes/donor.php'));

            // ✅ Matron Routes
            Route::middleware(['web', 'auth', 'role:matron'])
                ->prefix('matron')
                ->name('matron.')
                ->group(base_path('routes/matron.php'));

            // ✅ Testing Routes (local only)
            if ($this->app->environment(['local', 'staging', 'testing'])) {
                $this->loadRoutesFrom(base_path('routes/testing.php'));
            }
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}