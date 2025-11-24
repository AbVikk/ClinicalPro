<?php

try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    echo "Connected to Redis successfully!\n";
    
    // Test setting and getting a value
    $redis->set('test_key', 'Hello Redis!');
    $value = $redis->get('test_key');
    echo "Retrieved value: " . $value . "\n";
    
    $redis->close();
} catch (Exception $e) {
    echo "Failed to connect to Redis: " . $e->getMessage() . "\n";
}