<?php
/**
 * WordPress Connection Diagnostic Script
 * 
 * Place this file in your WordPress root directory and access it via browser:
 * http://localhost/wp-connection-test.php
 */

// Configuration
$service_url = 'http://127.0.0.1:3000';
$api_key = 'social-review-scraper-secret-2024-xyz789'; // Replace with your actual API key

echo '<h1>WordPress Connection Diagnostic</h1>';
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
</style>';

// Test 1: Basic PHP curl
echo '<div class="test-section">';
echo '<h2>Test 1: PHP cURL (Direct)</h2>';

if (function_exists('curl_init')) {
    $ch = curl_init($service_url . '/health');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo '<p class="error">❌ cURL Error: ' . htmlspecialchars($error) . '</p>';
    } else {
        echo '<p class="success">✅ cURL Success (HTTP ' . $http_code . ')</p>';
        echo '<pre>' . htmlspecialchars($response) . '</pre>';
    }
} else {
    echo '<p class="error">❌ cURL not available</p>';
}
echo '</div>';

// Test 2: file_get_contents
echo '<div class="test-section">';
echo '<h2>Test 2: file_get_contents</h2>';

$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($service_url . '/health', false, $context);

if ($response === false) {
    echo '<p class="error">❌ file_get_contents failed</p>';
    $error = error_get_last();
    if ($error) {
        echo '<pre>' . htmlspecialchars($error['message']) . '</pre>';
    }
} else {
    echo '<p class="success">✅ file_get_contents Success</p>';
    echo '<pre>' . htmlspecialchars($response) . '</pre>';
}
echo '</div>';

// Test 3: WordPress wp_remote_get (if WordPress is loaded)
echo '<div class="test-section">';
echo '<h2>Test 3: WordPress wp_remote_get</h2>';

// Try to load WordPress
$wp_load_paths = [
    __DIR__ . '/wp-load.php',
    dirname(__DIR__) . '/wp-load.php',
    dirname(dirname(__DIR__)) . '/wp-load.php',
];

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if ($wp_loaded && function_exists('wp_remote_get')) {
    echo '<p class="info">WordPress loaded successfully</p>';
    
    $response = wp_remote_get($service_url . '/health', [
        'timeout' => 10,
        'sslverify' => false
    ]);
    
    if (is_wp_error($response)) {
        echo '<p class="error">❌ wp_remote_get Error: ' . $response->get_error_message() . '</p>';
        echo '<p class="info">Error Code: ' . $response->get_error_code() . '</p>';
    } else {
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        echo '<p class="success">✅ wp_remote_get Success (HTTP ' . $code . ')</p>';
        echo '<pre>' . htmlspecialchars($body) . '</pre>';
    }
    
    // Test with API key
    echo '<h3>Test 3b: wp_remote_post with API Key</h3>';
    
    $response = wp_remote_post($service_url . '/api/scrape/google', [
        'headers' => [
            'Content-Type' => 'application/json',
            'X-API-Key' => $api_key,
        ],
        'body' => json_encode(['placeUrl' => 'test']),
        'timeout' => 10,
        'sslverify' => false
    ]);
    
    if (is_wp_error($response)) {
        echo '<p class="error">❌ wp_remote_post Error: ' . $response->get_error_message() . '</p>';
    } else {
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($code == 401) {
            echo '<p class="error">❌ API Key Invalid (HTTP 401)</p>';
        } elseif ($code == 400) {
            echo '<p class="success">✅ API Key Valid (HTTP 400 - expected for test request)</p>';
        } else {
            echo '<p class="info">HTTP ' . $code . '</p>';
        }
        echo '<pre>' . htmlspecialchars($body) . '</pre>';
    }
    
} else {
    echo '<p class="error">❌ WordPress not loaded - cannot test wp_remote_get</p>';
    echo '<p class="info">Place this file in your WordPress root directory</p>';
}
echo '</div>';

// Test 4: Server Information
echo '<div class="test-section">';
echo '<h2>Test 4: Server Information</h2>';
echo '<pre>';
echo 'PHP Version: ' . phpversion() . "\n";
echo 'cURL Available: ' . (function_exists('curl_init') ? 'Yes' : 'No') . "\n";
echo 'allow_url_fopen: ' . (ini_get('allow_url_fopen') ? 'Yes' : 'No') . "\n";
echo 'WordPress Loaded: ' . ($wp_loaded ? 'Yes' : 'No') . "\n";
echo 'Server Software: ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo '</pre>';
echo '</div>';

// Recommendations
echo '<div class="test-section">';
echo '<h2>Recommendations</h2>';
echo '<ul>';
echo '<li>If cURL works but wp_remote_get fails, check WordPress HTTP API settings</li>';
echo '<li>If all tests fail, check if the scraper service is running (npm start)</li>';
echo '<li>If you see "Connection refused", the service is not accessible</li>';
echo '<li>If you see "Could not resolve host", try using 127.0.0.1 instead of localhost</li>';
echo '<li>Check Windows Firewall settings for Node.js</li>';
echo '</ul>';
echo '</div>';

echo '<hr>';
echo '<p><strong>Next Step:</strong> Based on the results above, update your WordPress plugin settings accordingly.</p>';
?>
