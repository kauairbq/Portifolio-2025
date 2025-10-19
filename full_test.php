<?php
// Full test script for Portfolio2025 application

echo "<h1>Full Test for Portfolio2025</h1>";

// Test 1: Check key files existence
$files_to_check = [
    'index.html',
    'login.php',
    'admin.php',
    'logout.php',
    'register.php',
    'profile.php',
];

echo "<h2>File Existence Test</h2>";
foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<p style='color:green;'>File '$file' exists.</p>";
    } else {
        echo "<p style='color:red;'>File '$file' is missing.</p>";
    }
}

// Test 2: Test database connection
echo "<h2>Database Connection Test</h2>";
$host = 'localhost';
$db = 'Portfolio2025';
$user = 'root';
$pass = '';

$conn = @new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "<p style='color:red;'>Database connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>Database connection successful.</p>";
    $conn->close();
}

// Test 3: Test server PHP info
echo "<h2>PHP Info</h2>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Test 4: Test server response for test.php
echo "<h2>Server Test Script</h2>";
$test_script = 'test.php';
if (file_exists($test_script)) {
    $output = file_get_contents($test_script);
    echo "<p>Output of $test_script: <strong>" . htmlspecialchars($output) . "</strong></p>";
} else {
    echo "<p style='color:red;'>$test_script not found.</p>";
}

echo "<h2>Full Test Completed</h2>";
?>
