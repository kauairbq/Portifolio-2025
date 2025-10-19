<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'project_full_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter estrutura das tabelas
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

$export = "CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n\nUSE $db;\n\n";

foreach ($tables as $table) {
    // Estrutura da tabela
    $result = $conn->query("SHOW CREATE TABLE $table");
    $row = $result->fetch_row();
    $export .= $row[1] . ";\n\n";

    // Dados da tabela
    $result = $conn->query("SELECT * FROM $table");
    if ($result->num_rows > 0) {
        $export .= "INSERT INTO $table VALUES\n";
        $rows = [];
        while ($row = $result->fetch_row()) {
            $values = [];
            foreach ($row as $value) {
                $values[] = $value === null ? 'NULL' : "'" . $conn->real_escape_string($value) . "'";
            }
            $rows[] = "(" . implode(",", $values) . ")";
        }
        $export .= implode(",\n", $rows) . ";\n\n";
    }
}

file_put_contents('sql/export_database.sql', $export);
echo "Exportação concluída: sql/export_database.sql";
$conn->close();
?>
