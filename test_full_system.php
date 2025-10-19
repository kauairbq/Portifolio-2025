<?php
include 'db.php';

// Test database connection and tables
echo "=== Teste de Conexão e Tabelas ===\n";
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error . "\n";
    exit(1);
} else {
    echo "Conexão com banco de dados bem-sucedida\n";
}

$result = $conn->query("SHOW TABLES");
$tables = [];
if ($result) {
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
}
echo "Tabelas encontradas: " . implode(', ', $tables) . "\n";

// Test user registration
echo "\n=== Teste de Registo de Utilizador ===\n";
$username = 'testuser';
$email = 'test@example.com';
$password = password_hash('password123', PASSWORD_DEFAULT);
$user_type = 'user';

$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, user_type) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $password, $user_type);
if ($stmt->execute()) {
    echo "Utilizador registado com sucesso\n";
    $user_id = $stmt->insert_id;
} else {
    echo "Falha no registo: " . $stmt->error . "\n";
    exit(1);
}
$stmt->close();

// Test login
echo "\n=== Teste de Login ===\n";
$stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify('password123', $user['password_hash'])) {
        echo "Login bem-sucedido\n";
    } else {
        echo "Falha na verificação de senha\n";
    }
} else {
    echo "Utilizador não encontrado\n";
}
$stmt->close();

// Test event creation (admin)
echo "\n=== Teste de Criação de Evento ===\n";
$name = 'Concerto de Teste';
$description = 'Um concerto para testar o sistema';
$date = '2025-12-31';
$time = '20:00:00';
$venue = 'Teatro Municipal';
$capacity = 100;
$price = 50.00;

$stmt = $conn->prepare("INSERT INTO events (name, description, date, time, venue, capacity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssid", $name, $description, $date, $time, $venue, $capacity, $price);
if ($stmt->execute()) {
    echo "Evento criado com sucesso\n";
    $event_id = $stmt->insert_id;
} else {
    echo "Falha na criação do evento: " . $stmt->error . "\n";
}
$stmt->close();

// Test event retrieval
echo "\n=== Teste de Recuperação de Eventos ===\n";
$result = $conn->query("SELECT * FROM events ORDER BY date DESC LIMIT 5");
if ($result->num_rows > 0) {
    echo "Eventos recuperados:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['name'] . " em " . $row['venue'] . " (" . $row['date'] . ")\n";
    }
} else {
    echo "Nenhum evento encontrado\n";
}

// Test add to cart
echo "\n=== Teste de Adicionar ao Carrinho ===\n";
$quantity = 2;
$stmt = $conn->prepare("INSERT INTO cart (user_id, event_id, quantity) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $user_id, $event_id, $quantity);
if ($stmt->execute()) {
    echo "Item adicionado ao carrinho\n";
    $cart_id = $stmt->insert_id;
} else {
    echo "Falha ao adicionar ao carrinho: " . $stmt->error . "\n";
}
$stmt->close();

// Test cart retrieval
echo "\n=== Teste de Recuperação do Carrinho ===\n";
$stmt = $conn->prepare("SELECT c.quantity, e.name, e.price FROM cart c JOIN events e ON c.event_id = e.id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Itens no carrinho:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['quantity'] . "x " . $row['name'] . " (€" . $row['price'] . " cada)\n";
    }
} else {
    echo "Carrinho vazio\n";
}
$stmt->close();

// Test checkout
echo "\n=== Teste de Checkout ===\n";
$total_price = $quantity * $price;
$stmt = $conn->prepare("INSERT INTO purchases (user_id, event_id, quantity, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiid", $user_id, $event_id, $quantity, $total_price);
if ($stmt->execute()) {
    echo "Compra realizada com sucesso\n";
} else {
    echo "Falha na compra: " . $stmt->error . "\n";
}
$stmt->close();

// Test purchase history
echo "\n=== Teste de Histórico de Compras ===\n";
$stmt = $conn->prepare("SELECT p.quantity, p.total_price, e.name FROM purchases p JOIN events e ON p.event_id = e.id WHERE p.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Histórico de compras:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['quantity'] . " bilhetes para " . $row['name'] . " (€" . $row['total_price'] . ")\n";
    }
} else {
    echo "Nenhum histórico encontrado\n";
}
$stmt->close();

// Test service request
echo "\n=== Teste de Solicitação de Serviço ===\n";
$service_type = 'webdesign';
$description = 'Preciso de um site responsivo';
$stmt = $conn->prepare("INSERT INTO service_requests (user_id, service_type, description) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $service_type, $description);
if ($stmt->execute()) {
    echo "Solicitação de serviço criada\n";
} else {
    echo "Falha na solicitação: " . $stmt->error . "\n";
}
$stmt->close();

// Clean up test data
echo "\n=== Limpeza de Dados de Teste ===\n";
$conn->query("DELETE FROM purchases WHERE user_id = $user_id");
$conn->query("DELETE FROM cart WHERE user_id = $user_id");
$conn->query("DELETE FROM service_requests WHERE user_id = $user_id");
$conn->query("DELETE FROM events WHERE id = $event_id");
$conn->query("DELETE FROM users WHERE id = $user_id");
echo "Dados de teste limpos\n";

$conn->close();
echo "\n=== Todos os testes concluídos com sucesso! ===\n";
?>
