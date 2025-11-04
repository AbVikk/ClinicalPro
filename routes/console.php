<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // <-- VITAL: Import the Schedule Facade
use App\Console\Commands\SendDueReminders; // <-- VITAL: Import your Command Class

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
| This file is where you may define all of your Closure based console
| commands. The Schedule is also defined here in modern Laravel.
*/

// 1. DEFAULT COMMAND (Inspire)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 2. AI REMINDER SCHEDULER DEFINITION
Schedule::command(SendDueReminders::class)
    ->everyMinute()
    ->withoutOverlapping() // Ensures only one instance runs at a time
    ->runInBackground();