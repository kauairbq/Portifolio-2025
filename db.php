<?php
$host = getenv('MYSQLHOST') ?: 'localhost';
$db = getenv('MYSQLDATABASE') ?: 'project_full_db';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: null;
$port = getenv('MYSQLPORT') ?: 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

// Verifica conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para criar tabelas se não existirem
function criarTabela($conn, $sql, $nomeTabela) {
    $check = $conn->query("SHOW TABLES LIKE '$nomeTabela'");
    if ($check->num_rows == 0) {
        if ($conn->query($sql)) {
            error_log("Tabela '$nomeTabela' criada com sucesso.");
        } else {
            error_log("Erro ao criar tabela '$nomeTabela': " . $conn->error);
        }
    }
}

// Tabela de usuários (admin)
criarTabela($conn, "
    CREATE TABLE usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        cargo VARCHAR(50) DEFAULT 'admin',
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
", 'usuarios');

// Tabela de feedback
criarTabela($conn, "
    CREATE TABLE feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        mensagem TEXT NOT NULL,
        data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
", 'feedback');

// Tabela de clientes
criarTabela($conn, "
    CREATE TABLE clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(120) NOT NULL,
        email VARCHAR(150),
        telefone VARCHAR(30),
        projeto_id INT,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
", 'clientes');

// Tabela de serviços
criarTabela($conn, "
    CREATE TABLE servicos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(120) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2),
        ativo BOOLEAN DEFAULT TRUE,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
", 'servicos');

// Tabela de projetos
criarTabela($conn, "
    CREATE TABLE projetos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(120) NOT NULL,
        descricao TEXT,
        cliente_id INT,
        status ENUM('Planejado','Em andamento','Concluído') DEFAULT 'Planejado',
        data_inicio DATE,
        data_fim DATE,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
", 'projetos');

// Tabela de eventos (opcional)
criarTabela($conn, "
    CREATE TABLE eventos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(120) NOT NULL,
        descricao TEXT,
        data_evento DATETIME,
        tipo ENUM('Projeto','Serviço','Cliente','Outro') DEFAULT 'Outro',
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
", 'eventos');

?>
