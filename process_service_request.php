<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $tipo_servico = $_POST['tipo_servico'];
    $descricao = $_POST['descricao'];

    // Validate input
    if (empty($tipo_servico) || empty($descricao)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO service_requests (user_id, service_type, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $tipo_servico, $descricao);

    if ($stmt->execute()) {
        echo "Solicitação enviada com sucesso!";
    } else {
        echo "Erro ao enviar solicitação.";
    }

    $stmt->close();
    $conn->close();
}
?>
