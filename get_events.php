<?php
include 'db.php';

$query = "SELECT * FROM events ORDER BY date DESC LIMIT 6";
$result = $conn->query($query);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
$conn->close();
?>
