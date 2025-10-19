<?php
include 'db.php';

// Adicionar utilizador admin
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (username, email, password_hash, user_type) VALUES ('admin', 'admin@example.com', '$admin_password', 'admin') ON DUPLICATE KEY UPDATE id=id");

// Adicionar eventos de exemplo
$events = [
    ['Concerto de Rock', 'Um incrível concerto de rock com bandas locais.', '2024-12-01', '20:00', 'Estádio Municipal', 500, 25.00],
    ['Festival de Jazz', 'Festival anual de jazz com artistas internacionais.', '2024-11-15', '19:00', 'Centro Cultural', 300, 40.00],
    ['Teatro: Hamlet', 'Adaptação moderna da obra de Shakespeare.', '2024-10-20', '21:00', 'Teatro Nacional', 200, 15.00],
    ['Exposição de Arte', 'Exposição de arte contemporânea.', '2024-09-10', '10:00', 'Galeria de Arte', 100, 10.00],
    ['Workshop de Fotografia', 'Aprenda técnicas avançadas de fotografia.', '2024-08-25', '14:00', 'Centro de Formação', 50, 30.00],
    ['Show de Comédia', 'Noite de stand-up com comediantes famosos.', '2024-07-30', '20:30', 'Casa de Shows', 150, 20.00]
];

$stmt = $conn->prepare("INSERT INTO events (name, description, date, time, venue, capacity, price) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=id");

foreach ($events as $event) {
    $stmt->bind_param("sssssid", $event[0], $event[1], $event[2], $event[3], $event[4], $event[5], $event[6]);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Dados de exemplo adicionados com sucesso!";
?>
