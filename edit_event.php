<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, venue = ?, capacity = ?, price = ? WHERE id = ?");
    $stmt->bind_param("sssssidi", $name, $description, $date, $time, $venue, $capacity, $price, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Evento atualizado com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar evento.']);
    }

    $stmt->close();
    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        echo json_encode($event);
    } else {
        echo json_encode(['success' => false, 'message' => 'Evento não encontrado.']);
    }

    $stmt->close();
    $conn->close();
}
?>
