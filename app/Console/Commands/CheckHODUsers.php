<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckHODUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-hod-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check HOD users in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hodCount = User::where('role', 'hod')->count();
        $this->info("Number of HOD users: " . $hodCount);
        
        if ($hodCount > 0) {
            $hods = User::where('role', 'hod')->get();
            foreach ($hods as $hod) {
                $this->line("HOD: " . $hod->name . " (" . $hod->email . ")");
            }
        } else {
            $this->warn("No HOD users found in the system.");
        }
    }
}