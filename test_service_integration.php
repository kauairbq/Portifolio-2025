<?php
include 'db.php';

// Test database connection and table existence
echo "Testing database connection and service_requests table...\n";

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error . "\n";
    exit(1);
} else {
    echo "Database connection successful\n";
}

$result = $conn->query("SHOW TABLES LIKE 'service_requests'");
if ($result->num_rows > 0) {
    echo "service_requests table exists\n";
} else {
    echo "service_requests table does not exist\n";
    exit(1);
}

// Test inserting a sample service request
echo "\nTesting service request insertion...\n";
$user_id = 1; // Assuming user ID 1 exists
$service_type = 'webdesign';
$description = 'Test service request for web design';

$stmt = $conn->prepare("INSERT INTO service_requests (user_id, service_type, description) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $service_type, $description);

if ($stmt->execute()) {
    echo "Service request inserted successfully\n";
    $inserted_id = $stmt->insert_id;
} else {
    echo "Failed to insert service request: " . $stmt->error . "\n";
    exit(1);
}
$stmt->close();

// Test retrieving service requests
echo "\nTesting service request retrieval...\n";
$stmt = $conn->prepare("SELECT * FROM service_requests WHERE user_id = ? ORDER BY request_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Service requests retrieved successfully:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- ID: " . $row['id'] . ", Type: " . $row['service_type'] . ", Status: " . $row['status'] . "\n";
    }
} else {
    echo "No service requests found\n";
}

$stmt->close();

// Test updating status
echo "\nTesting status update...\n";
$new_status = 'in_progress';
$stmt = $conn->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
$stmt->bind_param("si", $new_status, $inserted_id);

if ($stmt->execute()) {
    echo "Status updated successfully\n";
} else {
    echo "Failed to update status: " . $stmt->error . "\n";
}

$stmt->close();

// Clean up test data
echo "\nCleaning up test data...\n";
$stmt = $conn->prepare("DELETE FROM service_requests WHERE id = ?");
$stmt->bind_param("i", $inserted_id);

if ($stmt->execute()) {
    echo "Test data cleaned up successfully\n";
} else {
    echo "Failed to clean up test data: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();

echo "\nAll database tests completed successfully!\n";
?>
