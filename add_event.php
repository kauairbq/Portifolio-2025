<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO events (name, description, date, time, venue, capacity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssid", $name, $description, $date, $time, $venue, $capacity, $price);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Evento adicionado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar evento']);
    }

    $stmt->close();
}

$conn->close();
?>
