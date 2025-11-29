<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SendDueReminders;
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
    ->withoutOverlapping() 
    ->runInBackground();
    
    // Run the robot every day at 8:00 AM
Schedule::command('appointments:remind')->dailyAt('08:00');