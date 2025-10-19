<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, user_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $user_type);

    if ($stmt->execute()) {
        echo "Registo bem-sucedido. <a href='login.php'>Login</a>";
    } else {
        echo "Erro no registo: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
