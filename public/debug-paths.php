<?php
// Debug script to check file paths

echo "<h1>Debug File Paths</h1>";

// Get the public path
$publicPath = __DIR__;
echo "<p>Public path: " . $publicPath . "</p>";

// Check build directory
$buildPath = $publicPath . '/build';
echo "<p>Build directory exists: " . (is_dir($buildPath) ? 'Yes' : 'No') . "</p>";

// Check manifest file
$manifestPath = $buildPath . '/manifest.json';
echo "<p>Manifest file exists: " . (file_exists($manifestPath) ? 'Yes' : 'No') . "</p>";

if (file_exists($manifestPath)) {
    echo "<p>Manifest file path: " . $manifestPath . "</p>";
    echo "<p>Manifest file size: " . filesize($manifestPath) . " bytes</p>";
    echo "<p>Manifest file content: " . file_get_contents($manifestPath) . "</p>";
}

// Check hot file
$hotPath = $publicPath . '/hot';
echo "<p>Hot file exists: " . (file_exists($hotPath) ? 'Yes' : 'No') . "</p>";

// Test the Laravel public_path() function equivalent
echo "<h2>Laravel public_path() equivalent test</h2>";
echo "<p>In Laravel, public_path('build/manifest.json') would resolve to: " . $publicPath . '/build/manifest.json' . "</p>";
echo "<p>This matches our manifest path: " . ($publicPath . '/build/manifest.json' === $manifestPath ? 'Yes' : 'No') . "</p>";
?>