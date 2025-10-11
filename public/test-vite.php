<?php
// Simple test file to check if Vite manifest issue is resolved

// Check if manifest file exists
$manifestPath = __DIR__ . '/build/manifest.json';
$hotPath = __DIR__ . '/hot';

$manifestExists = file_exists($manifestPath);
$hotExists = file_exists($hotPath);

echo "<h1>Vite Manifest Test</h1>";
echo "<p>Manifest file exists: " . ($manifestExists ? 'Yes' : 'No') . "</p>";
echo "<p>Hot file exists: " . ($hotExists ? 'Yes' : 'No') . "</p>";

if ($manifestExists) {
    echo "<p>Manifest content: " . file_get_contents($manifestPath) . "</p>";
}

echo "<p>If you can see this page without Vite errors, the manifest issue has been resolved.</p>";
?>