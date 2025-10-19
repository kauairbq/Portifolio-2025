<?php
session_start();
if (!isset($_SESSION["customer_id"])) {
    header("Location: login.php");
    exit;
}
include '../db.php';

$customer_id = $_SESSION["customer_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $data_nascimento = $_POST['data_nascimento'];
    $morada = trim($_POST['morada']);
    $info_pagamento = trim($_POST['info_pagamento']);

    $stmt = $conn->prepare("UPDATE customers SET nome=?, email=?, data_nascimento=?, morada=?, info_pagamento=? WHERE customer_id=?");
    $stmt->bind_param("sssssi", $nome, $email, $data_nascimento, $morada, $info_pagamento, $customer_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION["customer_nome"] = $nome;
    $sucesso = "Dados atualizados com sucesso!";
}

// Buscar dados atuais
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id=?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Dados | Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Editar Dados</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="solicitar_servico.php">Solicitar Serviço</a>
            <a href="editar_dados.php">Editar Dados</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="form-container">
        <h2>Atualizar Informações</h2>
        <?php if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>"; ?>
        <form method="POST">
            <label>Nome Completo:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" value="<?php echo $cliente['data_nascimento']; ?>" required>

            <label>Morada:</label>
            <input type="text" name="morada" value="<?php echo htmlspecialchars($cliente['morada']); ?>" required>

            <label>Informações de Pagamento:</label>
            <textarea name="info_pagamento"><?php echo htmlspecialchars($cliente['info_pagamento']); ?></textarea>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
