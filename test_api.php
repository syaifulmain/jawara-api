<?php

// Simple API test for Income Categories
echo "Testing Income Categories API\n";
echo "===================================\n";

$baseUrl = 'http://127.0.0.1:8000/api';

// Test 1: Get income category types (without auth)
echo "\n1. Testing GET /income-categories-types\n";
$response = file_get_contents("$baseUrl/income-categories-types");
if ($response) {
    echo "✓ Success: " . $response . "\n";
} else {
    echo "✗ Failed to get types\n";
}

// Test 2: Try to get categories without auth (should fail)
echo "\n2. Testing GET /income-categories (without auth)\n";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'ignore_errors' => true
    ]
]);
$response = file_get_contents("$baseUrl/income-categories", false, $context);
if ($response) {
    echo "Response: " . $response . "\n";
} else {
    echo "✓ Expected: Authentication required\n";
}

echo "\nAPI tests completed!\n";
echo "To test authenticated endpoints:\n";
echo "1. Login via POST /api/login\n";
echo "2. Use the token in Authorization: Bearer {token} header\n";
echo "3. Then test CRUD operations on /api/income-categories\n";