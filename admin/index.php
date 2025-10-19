<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $stmt = $conn->prepare("SELECT id, nome FROM users WHERE email = ? AND user_type = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome);
        $stmt->fetch();

        // Verificar senha (assumindo que está hashed)
        $stmt_pass = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt_pass->bind_param("i", $id);
        $stmt_pass->execute();
        $stmt_pass->bind_result($password_hash);
        $stmt_pass->fetch();

        if (password_verify($senha, $password_hash)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_nome"] = $nome;
            header("Location: dashboard.php");
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
        $stmt_pass->close();
    } else {
        $erro = "Usuário não encontrado ou não é administrador.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login | Painel Administrativo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Área Administrativa</h2>
        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
