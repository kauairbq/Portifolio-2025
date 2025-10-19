<?php
session_start();
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
$stmt->bind_param("i", $cart_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Removido do carrinho']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao remover']);
}

$stmt->close();
$conn->close();
?>
