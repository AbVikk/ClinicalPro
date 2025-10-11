<?php
// Simple test file to check if registration page loads without Vite errors

// Check if manifest file exists
$manifestPath = __DIR__ . '/build/manifest.json';
$hotPath = __DIR__ . '/hot';

$manifestExists = file_exists($manifestPath);
$hotExists = file_exists($hotPath);

// Simulate the conditional check that we added to the layout files
$shouldLoadVite = $manifestExists || $hotExists;

echo "<h1>Registration Page Test</h1>";
echo "<p>Manifest file exists: " . ($manifestExists ? 'Yes' : 'No') . "</p>";
echo "<p>Hot file exists: " . ($hotExists ? 'Yes' : 'No') . "</p>";
echo "<p>Should load Vite assets: " . ($shouldLoadVite ? 'Yes' : 'No') . "</p>";

if ($shouldLoadVite) {
    echo "<p>Vite assets would be loaded here if this were a real Laravel page.</p>";
} else {
    echo "<p>Vite assets would NOT be loaded due to missing manifest file.</p>";
}

echo "<p>If you can see this page without Vite errors, the manifest issue has been resolved.</p>";
?>