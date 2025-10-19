<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all service requests
$result = $conn->query("SELECT sr.*, u.username, u.email FROM service_requests sr JOIN users u ON sr.user_id = u.id ORDER BY sr.request_date DESC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitações de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1>Solicitações de Serviço</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Tipo de Serviço</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['service_type']; ?></td>
                    <td><?php echo substr($row['description'], 0, 50) . '...'; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pendente</option>
                                <option value="in_progress" <?php if ($row['status'] == 'in_progress') echo 'selected'; ?>>Em Progresso</option>
                                <option value="completed" <?php if ($row['status'] == 'completed') echo 'selected'; ?>>Concluído</option>
                                <option value="cancelled" <?php if ($row['status'] == 'cancelled') echo 'selected'; ?>>Cancelado</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Atualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin.php" class="btn btn-secondary">Voltar ao Admin</a>
    </div>
</body>
</html>

<?php
$result->close();
$conn->close();
?>
