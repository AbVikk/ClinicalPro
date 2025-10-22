<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Consultations table columns:\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('consultations');
print_r($columns);

echo "\nConsultation model fillable attributes:\n";
$consultation = new \App\Models\Consultation();
print_r($consultation->getFillable());