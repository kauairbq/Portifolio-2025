<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .dropdown-menu {
            background-color: #f8f9fa;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-menu .dropdown-item {
            color: #495057 !important;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-menu .dropdown-item:hover {
            color: #fff !important;
            background-color: #007bff;
            transform: translateX(5px);
        }

        .dropdown-menu .dropdown-item i {
            margin-right: 0.5rem;
            width: 1rem;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Eventos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index_eventos.php"><i class="bi bi-house"></i> Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php"><i class="bi bi-calendar-event"></i> Eventos</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php"><i class="bi bi-person"></i> Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Carrinho</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php"><i class="bi bi-person-plus"></i> Registar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Carrinho</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list"></i> Mais
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="index.html"><i class="bi bi-house"></i> Voltar ao Portfólio</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-light py-5">
        <div class="container text-center">
            <h1>Bem-vindo ao Sistema de Gestão de Eventos</h1>
            <p>Descubra e compre bilhetes para os melhores eventos.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <h2>Eventos Recentes</h2>
            <div class="row" id="eventos-recentes">
                <!-- Eventos serão carregados via JS -->
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
        // Carregar eventos recentes
        fetch('get_events.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('eventos-recentes');
                data.forEach(evento => {
                    const card = `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${evento.name}</h5>
                                    <p class="card-text">${evento.description.substring(0, 100)}...</p>
                                    <p>Data: ${evento.date} às ${evento.time}</p>
                                    <p>Preço: €${evento.price}</p>
                                    <a href="event_details.php?id=${evento.id}" class="btn btn-primary">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += card;
                });
            });
    </script>
</body>
</html>
