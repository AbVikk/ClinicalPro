<?php

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a service container
$container = new Container();
$app = $container;

// Create a database manager instance
$db = new Capsule($app);
$db->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'healthcare_system_v12',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods
$db->setAsGlobal();

// Setup the Eloquent ORM
$db->bootEloquent();

// Load the InventoryPrediction model
require_once 'app/Models/InventoryPrediction.php';

// Count predictions
$count = \App\Models\InventoryPrediction::count();
echo "Total predictions: " . $count . "\n";

// Get a sample prediction
$sample = \App\Models\InventoryPrediction::first();
if ($sample) {
    echo "Sample prediction:\n";
    echo "Drug: " . $sample->drug_name . "\n";
    echo "Month: " . $sample->month . "\n";
    echo "Year: " . $sample->year . "\n";
    echo "Predicted Quantity: " . $sample->predicted_quantity . "\n";
    echo "Reorder Point: " . $sample->reorder_point . "\n";
    echo "Recommended Order Quantity: " . $sample->recommended_order_quantity . "\n";
} else {
    echo "No predictions found.\n";
}