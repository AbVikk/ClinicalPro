<?php
require_once 'vendor/autoload.php';

// Bootstrap the application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Drug;

try {
    $count = Drug::count();
    echo "Drugs count: " . $count . "\n";
    
    if ($count > 0) {
        $drugs = Drug::limit(10)->get();
        echo "Sample drugs:\n";
        foreach($drugs as $drug) {
            echo $drug->id . ': ' . $drug->name . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>