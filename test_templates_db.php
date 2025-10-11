<?php
require_once 'vendor/autoload.php';

// Bootstrap the application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test database access
try {
    $templates = \App\Models\PrescriptionTemplate::with('creator')->get();
    echo "Templates count: " . $templates->count() . "\n";
    
    if ($templates->count() > 0) {
        $template = $templates->first();
        echo "First template name: " . $template->name . "\n";
        echo "Creator name: " . ($template->creator ? $template->creator->name : 'No creator') . "\n";
        echo "Medications: " . print_r($template->medications, true) . "\n";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>