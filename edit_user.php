<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, user_type = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $user_type, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Utilizador atualizado com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar utilizador.']);
    }

    $stmt->close();
    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['success' => false, 'message' => 'Utilizador não encontrado.']);
    }

    $stmt->close();
    $conn->close();
}
?>
