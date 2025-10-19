<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $data_nascimento = $_POST['data_nascimento'];
    $morada = trim($_POST['morada']);
    $info_pagamento = trim($_POST['info_pagamento']);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, profile_pic, user_type) VALUES (?, ?, ?, '', 'user')");
    $stmt->bind_param("sss", $nome, $email, $senha);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Inserir dados adicionais na tabela customers
        $stmt2 = $conn->prepare("INSERT INTO customers (customer_id, nome, email, data_nascimento, morada, info_pagamento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("isssss", $user_id, $nome, $email, $data_nascimento, $morada, $info_pagamento);
        $stmt2->execute();
        $stmt2->close();

        header("Location: login.php");
        exit;
    } else {
        $erro = "Erro ao registrar: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registro | Área do Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Registrar-se</h2>
        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <form method="POST">
            <label>Nome Completo:</label>
            <input type="text" name="nome" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required>

            <label>Morada:</label>
            <input type="text" name="morada" required>

            <label>Informações de Pagamento:</label>
            <textarea name="info_pagamento" placeholder="Ex: Cartão de crédito, PayPal, etc."></textarea>

            <button type="submit">Registrar</button>
        </form>
        <p>Já tem conta? <a href="login.php">Fazer login</a></p>
    </div>
</body>
</html>
