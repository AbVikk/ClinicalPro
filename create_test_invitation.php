<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Str;
use App\Models\Invitation;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a test invitation
$token = Str::random(50);
$invitation = Invitation::create([
    'token' => $token,
    'email' => 'test@example.com',
    'role' => 'doctor',
    'expires_at' => now()->addDays(7)
]);

echo "Created invitation with token: " . $invitation->token . "\n";
echo "Registration URL: " . route('invitations.register', ['token' => $invitation->token]) . "\n";