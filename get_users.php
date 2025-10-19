<?php
include 'db.php';

$query = "SELECT id, username, email, user_type FROM users";
$result = $conn->query($query);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();
?>
