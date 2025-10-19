<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Sistema de Eventos</title>
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
            <a class="navbar-brand" href="index_eventos.php">Sistema de Eventos</a>
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

    <section class="py-5">
        <div class="container">
            <h2>Eventos Disponíveis</h2>
            <div class="mb-3">
                <input type="text" id="search" class="form-control" placeholder="Pesquisar por título ou data">
            </div>
            <div class="row" id="eventos-lista">
                <!-- Eventos serão carregados via JS -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container text-center">

            <!-- Connect With Me Section -->
            <div id="connect" class="py-3">
                <h2>Conecte-se Comigo</h2>
                <!-- Social Media Icons -->
                <a href="https://github.com/kauairbq" target="_blank" rel="noopener noreferrer" class="mx-2" aria-label="GitHub">
                    <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" alt="GitHub" width="32" height="32">
                </a>
                <a href="https://www.facebook.com/kauai.rocha.10/" target="_blank" rel="noopener noreferrer" class="mx-2" aria-label="Facebook">
                    <img src="https://www.facebook.com/images/fb_icon_325x325.png" alt="Facebook" width="32" height="32">
                </a>
                <a href="https://www.instagram.com/ka_roochaa/" target="_blank" rel="noopener noreferrer" class="mx-2" aria-label="Instagram">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" width="32" height="32">
                </a>
                <a href="https://www.linkedin.com/in/kauai-lucas-rocha-bozoli-quinup/" target="_blank" rel="noopener noreferrer" class="mx-2" aria-label="LinkedIn">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" alt="LinkedIn" width="32" height="32">
                </a></a>


            <!-- Complementos Didáticos Section -->
            <div id="complementos" class="py-3">
                <h2>Complementos Didáticos</h2>
                <ul>
                    <li><a href="https://olhardigital.com.br/" target="_blank" rel="noopener noreferrer">Notícias Olhar Digital: Acesse as últimas novidades em tecnologia.</a></li>
                    <li><a href="https://cursoemvideo.com/" target="_blank" rel="noopener noreferrer">Curso em Vídeo: Cursos Grátis para iniciar na programação.</a></li>
                    <li><a href="https://www.masterd.pt/" target="_blank" rel="noopener noreferrer">Master D: Melhor instituição profissionalizante em Portugal.</a></li>
                </ul>
            </div>

            <p>&copy; 2024 Sistema de Eventos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allEvents = [];

        function loadEvents() {
            fetch('get_events.php')
                .then(response => response.json())
                .then(data => {
                    allEvents = data;
                    displayEvents(data);
                });
        }

        function displayEvents(events) {
            const container = document.getElementById('eventos-lista');
            container.innerHTML = '';
            events.forEach(evento => {
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
        }

        document.getElementById('search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const filtered = allEvents.filter(event =>
                event.name.toLowerCase().includes(query) || event.date.includes(query)
            );
            displayEvents(filtered);
        });

        loadEvents();
    </script>
</body>
</html>
