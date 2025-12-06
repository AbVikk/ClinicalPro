<?php

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\DrugBatch;

echo "Checking drug batches...\n";

$count = DrugBatch::count();
echo "Total drug batches: " . $count . "\n";

if ($count > 0) {
    $batches = DrugBatch::with('drug')->take(5)->get();
    echo "Sample batches:\n";
    foreach ($batches as $batch) {
        echo "- Batch UUID: " . $batch->batch_uuid . "\n";
        echo "  Drug: " . ($batch->drug ? $batch->drug->name : 'N/A') . "\n";
        echo "  Quantity: " . $batch->received_quantity . "\n";
        echo "  Expiry: " . $batch->expiry_date . "\n";
        echo "---\n";
    }
}