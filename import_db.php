<?php
$host = 'localhost';
$user = 'root';
$pass = null;
$db = 'project_full_db';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Criar banco se não existir
$conn->query("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($db);

// Ler o arquivo SQL
$sql = file_get_contents('sql/full_project_database.sql');

// Remover comentários e dividir em statements
$sql = preg_replace('/--.*$/m', '', $sql); // Remove comentários de linha
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove comentários de bloco

$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $statement) {
    if (!empty($statement)) {
        // Ignorar CREATE DATABASE se já existir
        if (stripos($statement, 'CREATE DATABASE') !== false) {
            $conn->query($statement);
            continue;
        }
        // Usar IF NOT EXISTS para tabelas
        if (stripos($statement, 'CREATE TABLE') !== false && stripos($statement, 'IF NOT EXISTS') === false) {
            $statement = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $statement);
        }
        // Para views, usar DROP IF EXISTS antes de CREATE
        if (stripos($statement, 'CREATE VIEW') !== false) {
            // Extrair nome da view
            preg_match('/CREATE VIEW (\w+)/i', $statement, $matches);
            if (isset($matches[1])) {
                $view_name = $matches[1];
                $conn->query("DROP VIEW IF EXISTS $view_name");
            }
        }
        if ($conn->query($statement) === TRUE) {
            echo "Statement executado com sucesso.\n";
        } else {
            echo "Erro: " . $conn->error . "\n";
        }
    }
}

$conn->close();
echo "Importação concluída.";
?>
