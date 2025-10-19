<?php
include 'db.php';

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$sql = "SELECT 1";
if ($result = $conn->query($sql)) {
    echo "Conexão e consulta MySQLi bem-sucedidas.";
    $result->free();
} else {
    echo "Erro na consulta MySQLi: " . $conn->error;
}

$conn->close();
