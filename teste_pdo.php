<?php
$host = 'localhost';
$db = 'project_full_db';
$user = 'root'; // Alterar conforme necessário
$pass = ''; // Alterar conforme necessário
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // Configura o modo de erro do PDO para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Executa uma consulta simples para testar a conexão
    $stmt = $pdo->query("SELECT 1");
    if ($stmt) {
        echo "Conexão e consulta PDO bem-sucedidas.";
    } else {
        echo "Erro na consulta PDO.";
    }
} catch (PDOException $e) {
    echo "Falha na conexão PDO: " . $e->getMessage();
}
