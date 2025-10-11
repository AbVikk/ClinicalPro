<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateTestInvitation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-test-invitation {email?} {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a test invitation for the healthcare system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        $role = $this->argument('role') ?? 'doctor';
        
        $token = \Illuminate\Support\Str::random(50);
        $invitation = \App\Models\Invitation::create([
            'token' => $token,
            'email' => $email,
            'role' => $role,
            'expires_at' => now()->addDays(7)
        ]);
        
        $this->info('Created invitation with token: ' . $invitation->token);
        $this->info('Registration URL: ' . route('invitations.register', ['token' => $invitation->token]));
    }
}
