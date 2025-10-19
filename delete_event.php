<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Evento excluído']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao excluir evento']);
}

$stmt->close();
$conn->close();
?>
