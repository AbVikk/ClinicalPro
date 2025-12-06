<?php

use Illuminate\Console\Command;
use App\Models\User;

class CheckFillableCommand extends Command
{
    protected $signature = 'check:fillable';
    protected $description = 'Check if clinic_id is in User fillable array';

    public function handle()
    {
        $user = new User();
        $fillable = $user->getFillable();
        
        if (in_array('clinic_id', $fillable)) {
            $this->info('PASS: clinic_id is in the fillable array');
            $this->info('Fillable attributes: ' . implode(', ', $fillable));
            return 0;
        } else {
            $this->error('FAIL: clinic_id is NOT in the fillable array');
            $this->error('Fillable attributes: ' . implode(', ', $fillable));
            return 1;
        }
    }
}