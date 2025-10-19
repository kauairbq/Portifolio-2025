<?php
// Thorough test script for Portfolio2025 application

function http_request($url, $post_data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($post_data !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $http_code, 'header' => $header, 'body' => $body];
}

function test_page($url) {
    echo "<h3>Testing page: $url</h3>";
    $result = http_request($url);
    if ($result['code'] === 200) {
        echo "<p style='color:green;'>Success: HTTP 200 OK</p>";
    } else {
        echo "<p style='color:red;'>Failed: HTTP " . $result['code'] . "</p>";
    }
}

function test_login($url, $post_data) {
    echo "<h3>Testing login at: $url</h3>";
    $result = http_request($url, $post_data);
    if ($result['code'] === 200) {
        $json = json_decode($result['body'], true);
        if ($json && isset($json['success']) && $json['success'] === true) {
            echo "<p style='color:green;'>Login test passed</p>";
            return;
        }
    }
    echo "<p style='color:red;'>Login test failed</p>";
}

// Base URL for local testing - adjust if needed
$base_url = "http://localhost/portifolio2025/";

// List of pages to test
$pages = [
    "index.html",
    "login.php",
    "admin.php",
    "logout.php",
    "register.php",
    "profile.php",
    "projects.json",
    "servicos.html",
];

// Run page tests
echo "<h2>Page Accessibility Tests</h2>";
foreach ($pages as $page) {
    test_page($base_url . $page);
}

// Test login with dummy credentials
echo "<h2>Login Functionality Test</h2>";
$login_url = $base_url . "process_login.php";
$login_data = [
    'email' => 'testuser@example.com',
    'password' => 'testpassword'
];
test_login($login_url, $login_data);

// Additional tests can be added here for registration, profile update, etc.

// Test registration with dummy data
echo "<h2>Registration Functionality Test</h2>";
$register_url = $base_url . "process_register.php";
$register_data = [
    'email' => 'newuser@example.com',
    'name' => 'Test User',
    'phone' => '1234567890'
];
test_page($register_url); // Check if registration page is accessible
$result = http_request($register_url, $register_data);
if ($result['code'] === 200) {
    $json = json_decode($result['body'], true);
    if ($json && isset($json['success']) && $json['success'] === true) {
        echo "<p style='color:green;'>Registration test passed</p>";
    } else {
        echo "<p style='color:red;'>Registration test failed</p>";
    }
} else {
    echo "<p style='color:red;'>Registration test failed</p>";
}

// Test profile update with dummy data
echo "<h2>Profile Update Test</h2>";
$profile_update_url = $base_url . "profile.php";
$profile_update_data = [
    'name' => 'Updated User',
    'email' => 'newuser@example.com'
];
$result = http_request($profile_update_url, $profile_update_data);
if ($result['code'] === 200) {
    echo "<p style='color:green;'>Profile update test passed</p>";
} else {
    echo "<p style='color:red;'>Profile update test failed</p>";
}

// Edge case tests

echo "<h2>Edge Case Tests</h2>";

// Login with missing email
echo "<h3>Login Test - Missing Email</h3>";
$login_data_missing_email = [
    'password' => 'testpassword'
];
$result = http_request($login_url, $login_data_missing_email);
if ($result['code'] === 200) {
    $json = json_decode($result['body'], true);
    if ($json && isset($json['success']) && $json['success'] === false && strpos($json['message'], 'obrigatórios') !== false) {
        echo "<p style='color:green;'>Passed: Proper error message for missing email</p>";
    } else {
        echo "<p style='color:red;'>Failed: Missing email error not handled correctly</p>";
    }
} else {
    echo "<p style='color:red;'>Failed: Missing email error not handled correctly</p>";
}

// Login with missing password
echo "<h3>Login Test - Missing Password</h3>";
$login_data_missing_password = [
    'email' => 'testuser@example.com'
];
$result = http_request($login_url, $login_data_missing_password);
if ($result['code'] === 200) {
    $json = json_decode($result['body'], true);
    if ($json && isset($json['success']) && $json['success'] === false && strpos($json['message'], 'obrigatórios') !== false) {
        echo "<p style='color:green;'>Passed: Proper error message for missing password</p>";
    } else {
        echo "<p style='color:red;'>Failed: Missing password error not handled correctly</p>";
    }
} else {
    echo "<p style='color:red;'>Failed: Missing password error not handled correctly</p>";
}

// Registration with missing fields
echo "<h3>Registration Test - Missing Fields</h3>";
$register_data_missing = [
    'email' => '',
    'name' => '',
    'phone' => ''
];
$result = http_request($register_url, $register_data_missing);
if ($result['code'] === 200) {
    $json = json_decode($result['body'], true);
    if ($json && isset($json['success']) && $json['success'] === false) {
        echo "<p style='color:green;'>Passed: Proper error handling for missing registration fields</p>";
    } else {
        echo "<p style='color:red;'>Failed: Missing registration fields error not handled correctly</p>";
    }
} else {
    echo "<p style='color:red;'>Failed: Missing registration fields error not handled correctly</p>";
}

echo "<h2>Thorough Test Completed</h2>";
?>
