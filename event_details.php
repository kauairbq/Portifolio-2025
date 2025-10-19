<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Evento - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index_eventos.php">Sistema de Eventos</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="events.php">Eventos</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Perfil</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Carrinho</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Registar</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Carrinho</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div id="evento-detalhes">
                <!-- Detalhes do evento serão carregados via JS -->
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>&copy; 2024 Sistema de Eventos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const eventId = urlParams.get('id');

        fetch(`get_event_details.php?id=${eventId}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('evento-detalhes');
                container.innerHTML = `
                    <h2>${data.name}</h2>
                    <p>${data.description}</p>
                    <p><strong>Data:</strong> ${data.date}</p>
                    <p><strong>Hora:</strong> ${data.time}</p>
                    <p><strong>Local:</strong> ${data.venue}</p>
                    <p><strong>Capacidade:</strong> ${data.capacity}</p>
                    <p><strong>Preço:</strong> €${data.price}</p>
                    <button class="btn btn-success" onclick="addToCart(${data.id})">Adicionar ao Carrinho</button>
                `;
            });

        function addToCart(eventId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ event_id: eventId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
        }
    </script>
</body>
</html>
