<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilizador não autenticado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$event_id = $data['event_id'];

$stmt = $conn->prepare("INSERT INTO cart (user_id, event_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
$stmt->bind_param("ii", $user_id, $event_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Adicionado ao carrinho']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar ao carrinho']);
}

$stmt->close();
$conn->close();
?>
