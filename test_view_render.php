<?php
require_once 'vendor/autoload.php';

// Bootstrap the application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate an authenticated admin user
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PrescriptionTemplate;

// Log in as admin
$admin = User::where('role', 'admin')->first();
if ($admin) {
    Auth::login($admin);
    echo "Logged in as admin: " . $admin->email . "\n";
    
    // Get template data
    $allTemplates = PrescriptionTemplate::with('creator')->get();
    $recentlyUsed = PrescriptionTemplate::with('creator')
        ->where('updated_at', '>=', now()->subDays(30))
        ->get();
    $myTemplates = PrescriptionTemplate::with('creator')
        ->where('created_by', $admin->id)
        ->get();
    $sampleTemplate = PrescriptionTemplate::with('creator')->first();
    
    // Enhance sample template with drug names
    if ($sampleTemplate && is_array($sampleTemplate->medications)) {
        $medications = $sampleTemplate->medications;
        foreach ($medications as &$medication) {
            if (isset($medication['drug_id'])) {
                $drug = \App\Models\Drug::find($medication['drug_id']);
                if ($drug) {
                    $medication['drug_name'] = $drug->name;
                }
            }
        }
        $sampleTemplate->medications = $medications;
    }
    
    try {
        // Try to render the view
        $view = view('admin.prescriptions.templates', compact('allTemplates', 'recentlyUsed', 'myTemplates', 'sampleTemplate'));
        $rendered = $view->render();
        echo "View rendered successfully\n";
        echo "Rendered content length: " . strlen($rendered) . " characters\n";
    } catch (Exception $e) {
        echo "View rendering error: " . $e->getMessage() . "\n";
        echo "Error trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "No admin user found\n";
}
?>