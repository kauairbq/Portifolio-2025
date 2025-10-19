<?php
include 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel | Admin</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="dashboard">
  <h1>Bem-vindo, <?php echo $_SESSION["user_nome"]; ?> 👋</h1>
  <div class="cards">
    <a href="clientes.php" class="card">👤 Clientes</a>
    <a href="servicos.php" class="card">🛠 Serviços</a>
    <a href="projetos.php" class="card">💻 Projetos</a>
    <a href="feedbacks.php" class="card">💬 Feedbacks</a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
