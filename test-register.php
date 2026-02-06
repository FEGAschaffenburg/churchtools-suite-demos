<?php
/**
 * Test registration process
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Simulate registration data
$data = [
    'email' => 'test-' . time() . '@example.com',
    'first_name' => 'Test',
    'last_name' => 'User',
    'company' => 'Test Company',
    'purpose' => 'Testing',
    'password' => 'Test123!@#',
    'password_confirm' => 'Test123!@#',
];

echo "=== Testing Registration Process ===\n\n";

// Load service classes
require_once __DIR__ . '/includes/repositories/class-demo-users-repository.php';
require_once __DIR__ . '/includes/services/class-demo-registration-service.php';

// Initialize
$repo = new ChurchTools_Suite_Demo_Users_Repository();
$service = new ChurchTools_Suite_Demo_Registration_Service($repo);

echo "Service initialized.\n";
echo "Attempting registration...\n\n";

// Try registration
try {
    $result = $service->register_user($data);
    
    if (is_wp_error($result)) {
        echo "❌ ERROR: " . $result->get_error_message() . "\n";
        echo "Error Code: " . $result->get_error_code() . "\n";
    } else {
        echo "✅ SUCCESS!\n";
        echo "Demo User ID: " . $result['demo_user_id'] . "\n";
        if (isset($result['wp_user_id'])) {
            echo "WP User ID: " . $result['wp_user_id'] . "\n";
            echo "Username: " . $result['username'] . "\n";
            echo "Auto-Login: " . ($result['auto_login'] ? 'Yes' : 'No') . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
