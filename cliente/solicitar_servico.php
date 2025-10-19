<?php
session_start();
if (!isset($_SESSION["customer_id"])) {
    header("Location: login.php");
    exit;
}
include '../db.php';

// Buscar serviços disponíveis
$result = $conn->query("SELECT id, name, price FROM events WHERE capacity > 0"); // Usando events como serviços por enquanto
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Serviço</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Solicitar Serviço</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="solicitar_servico.php">Solicitar Serviço</a>
            <a href="editar_dados.php">Editar Dados</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="form-container">
        <h2>Escolha um Serviço</h2>
        <form method="POST" action="../api/solicitacao.php">
            <label>Serviço:</label>
            <select name="servico_id" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?> - €<?php echo number_format($row['price'], 2); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Observações:</label>
            <textarea name="observacoes"></textarea>

            <button type="submit">Solicitar</button>
        </form>
    </div>
</body>
</html>
