<?php
session_start();
if (!isset($_SESSION["customer_id"])) {
    header("Location: login.php");
    exit;
}
include '../db.php';

$customer_id = $_SESSION["customer_id"];
$nome = $_SESSION["customer_nome"];

// Buscar histórico de solicitações
$result = $conn->prepare("SELECT s.id, se.name AS servico, s.status, s.data_solicitacao FROM solicitacoes s JOIN events se ON s.servico_id = se.id WHERE s.customer_id = ? ORDER BY s.data_solicitacao DESC");
$result->bind_param("i", $customer_id);
$result->execute();
$solicitacoes = $result->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Bem-vindo, <?php echo $nome; ?>!</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="solicitar_servico.php">Solicitar Serviço</a>
            <a href="editar_dados.php">Editar Dados</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="dashboard">
        <h2>Histórico de Solicitações</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $solicitacoes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['servico']); ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['data_solicitacao'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
