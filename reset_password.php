<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Por favor, insira um e-mail válido.';
    } else {
        // Check if email exists
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($user_id);
        if ($stmt->fetch()) {
            $stmt->close();

            // Generate token and expiration (1 hour)
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600);

            // Store token and expiration in password_resets table (create if not exists)
            $stmt = $conn->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $user_id, $token, $expires);
            $stmt->execute();
            $stmt->close();

            // Send email with reset link
            $reset_link = "http://localhost:8000/new_password.php?token=$token";
            $subject = 'Redefinição de senha';
            $body = "Clique no link para redefinir sua senha: $reset_link\nEste link expira em 1 hora.";
            $headers = 'From: no-reply@seusite.com' . "\r\n" .
                'Reply-To: no-reply@seusite.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($email, $subject, $body, $headers);

            $message = 'Um link para redefinir a senha foi enviado para seu e-mail.';
        } else {
            $message = 'E-mail não encontrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Redefinir Senha</title>
    <style>
        body {
            background-color: #212226;
            color: #fff;
            font-family: "Lato", sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
        }

        label,
        input,
        button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }

        input,
        button {
            padding: 10px;
            border-radius: 4px;
            border: none;
        }

        button {
            background-color: #CFF250;
            color: #212226;
            cursor: pointer;
        }

        button:hover {
            background-color: #a4b72c;
        }

        .message {
            margin-bottom: 15px;
            color: #ffc107;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Redefinir Senha</h1>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="POST" action="reset_password.php">
            <label for="email">Digite seu e-mail cadastrado:</label>
            <input type="email" id="email" name="email" required />
            <button type="submit">Enviar link de redefinição</button>
        </form>
        <p><a href="login.php">Voltar ao login</a></p>
    </div>
</body>

</html>