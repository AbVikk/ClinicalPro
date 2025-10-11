<?php
// Simple script to test the templates redirect route
$url = 'http://127.0.0.1:8000/templates';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects

$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

echo "Status Code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
echo "Headers:\n" . $header . "\n";

curl_close($ch);
?>