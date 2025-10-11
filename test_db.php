<?php
require_once 'vendor/autoload.php';

// Bootstrap the application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PrescriptionTemplate;

try {
    $template = new PrescriptionTemplate();
    $template->name = 'Test Template';
    $template->created_by = 1;
    $template->medications = [['name' => 'Test Med', 'dosage' => '10mg']];
    $template->save();
    echo "Template created successfully\n";
    
    // Retrieve the template
    $savedTemplate = PrescriptionTemplate::find($template->id);
    echo "Retrieved template: " . $savedTemplate->name . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>