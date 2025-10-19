<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilizador não autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.event_id, e.price, c.quantity FROM cart c JOIN events e ON c.event_id = e.id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->begin_transaction();

try {
    while ($row = $result->fetch_assoc()) {
        $event_id = $row['event_id'];
        $quantity = $row['quantity'];
        $total_price = $row['price'] * $quantity;

        $insert_purchase = $conn->prepare("INSERT INTO purchases (user_id, event_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $insert_purchase->bind_param("iiid", $user_id, $event_id, $quantity, $total_price);
        $insert_purchase->execute();
        $insert_purchase->close();
    }

    $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $delete_cart->bind_param("i", $user_id);
    $delete_cart->execute();
    $delete_cart->close();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Compra finalizada com sucesso']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Erro na compra']);
}

$stmt->close();
$conn->close();
?>
