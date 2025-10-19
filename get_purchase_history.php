<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT p.quantity, p.total_price, p.purchase_date, e.name AS event_name FROM purchases p JOIN events e ON p.event_id = e.id WHERE p.user_id = ? ORDER BY p.purchase_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}

echo json_encode($history);
$stmt->close();
$conn->close();
?>
