<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Connect to portfolio_db
$portfolioDb = new mysqli('localhost', 'root', '', 'portfolio_db');
if ($portfolioDb->connect_error) {
    die("Connection failed: " . $portfolioDb->connect_error);
}

// Fetch projects with category names
$sql = "SELECT p.id, p.title, p.description, c.name AS category_name, p.creation_date
        FROM projects p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.creation_date DESC";
$result = $portfolioDb->query($sql);

$projects = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}
$portfolioDb->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Administração do Portfólio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #212226;
            color: #fff;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #CFF250;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #CFF250;
            color: #212226;
        }

        tr:nth-child(even) {
            background-color: #333;
        }

        h1 {
            color: #CFF250;
        }
    </style>
</head>

<body>
    <h1>Administração do Portfólio</h1>
    <?php if (count($projects) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['title']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                        <td><?php echo htmlspecialchars($project['category_name'] ?? 'Sem Categoria'); ?></td>
                        <td><?php echo htmlspecialchars($project['creation_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum projeto encontrado.</p>
    <?php endif; ?>
</body>

</html>