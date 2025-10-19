<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Sistema de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index_eventos.php">Sistema de Eventos - Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <h2>Painel de Administração</h2>
            <div class="row">
                <div class="col-md-6">
                    <h3>Gerir Eventos</h3>
                    <button class="btn btn-primary mb-3" onclick="showAddEventForm()">Adicionar Evento</button>
                    <div id="eventos-admin">
                        <!-- Lista de eventos para admin -->
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Gerir Utilizadores</h3>
                    <div id="utilizadores-admin">
                        <!-- Lista de utilizadores -->
                    </div>
                    <h3>Gerir Solicitações de Serviço</h3>
                    <a href="admin_service_requests.php" class="btn btn-secondary mb-3">Ver Solicitações</a>
                </div>
            </div>
        </div>
    </section>

    <div id="add-event-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Adicionar Evento</h3>
            <form id="add-event-form">
                <input type="text" name="name" placeholder="Nome" required>
                <textarea name="description" placeholder="Descrição" required></textarea>
                <input type="date" name="date" required>
                <input type="time" name="time" required>
                <input type="text" name="venue" placeholder="Local" required>
                <input type="number" name="capacity" placeholder="Capacidade" required>
                <input type="number" step="0.01" name="price" placeholder="Preço" required>
                <button type="submit">Adicionar</button>
            </form>
        </div>
    </div>

    <div id="edit-event-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Editar Evento</h3>
            <form id="edit-event-form">
                <input type="hidden" name="id">
                <input type="text" name="name" placeholder="Nome" required>
                <textarea name="description" placeholder="Descrição" required></textarea>
                <input type="date" name="date" required>
                <input type="time" name="time" required>
                <input type="text" name="venue" placeholder="Local" required>
                <input type="number" name="capacity" placeholder="Capacidade" required>
                <input type="number" step="0.01" name="price" placeholder="Preço" required>
                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>

    <div id="edit-user-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditUserModal()">&times;</span>
            <h3>Editar Utilizador</h3>
            <form id="edit-user-form">
                <input type="hidden" name="id">
                <input type="text" name="username" placeholder="Nome de Utilizador" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="user_type" required>
                    <option value="user">Utilizador</option>
                    <option value="admin">Administrador</option>
                </select>
                <button type="submit">Atualizar</button>
            </form>
        </div>
    </div>

    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>&copy; 2024 Sistema de Eventos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadAdminData() {
            fetch('get_events.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('eventos-admin');
                    container.innerHTML = '';
                    data.forEach(evento => {
                        container.innerHTML += `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>${evento.name}</h5>
                                    <button class="btn btn-warning" onclick="editEvent(${evento.id})">Editar</button>
                                    <button class="btn btn-danger" onclick="deleteEvent(${evento.id})">Excluir</button>
                                </div>
                            </div>
                        `;
                    });
                });

            fetch('get_users.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('utilizadores-admin');
                    container.innerHTML = '';
                    data.forEach(user => {
                        container.innerHTML += `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>${user.username}</h5>
                                    <p>${user.email}</p>
                                    <button class="btn btn-warning" onclick="editUser(${user.id})">Editar</button>
                                    <button class="btn btn-danger" onclick="deleteUser(${user.id})">Excluir</button>
                                </div>
                            </div>
                        `;
                    });
                });
        }

        function showAddEventForm() {
            document.getElementById('add-event-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('add-event-modal').style.display = 'none';
        }

        function closeEditModal() {
            document.getElementById('edit-event-modal').style.display = 'none';
        }

        function closeEditUserModal() {
            document.getElementById('edit-user-modal').style.display = 'none';
        }

        document.getElementById('add-event-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('add_event.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                closeModal();
                loadAdminData();
            });
        });

        function editEvent(id) {
            fetch(`edit_event.php?id=${id}`)
                .then(response => response.json())
                .then(event => {
                    if (event.success === false) {
                        alert(event.message);
                        return;
                    }
                    document.getElementById('edit-event-form').elements['id'].value = event.id;
                    document.getElementById('edit-event-form').elements['name'].value = event.name;
                    document.getElementById('edit-event-form').elements['description'].value = event.description;
                    document.getElementById('edit-event-form').elements['date'].value = event.date;
                    document.getElementById('edit-event-form').elements['time'].value = event.time;
                    document.getElementById('edit-event-form').elements['venue'].value = event.venue;
                    document.getElementById('edit-event-form').elements['capacity'].value = event.capacity;
                    document.getElementById('edit-event-form').elements['price'].value = event.price;
                    document.getElementById('edit-event-modal').style.display = 'block';
                });
        }

        function editUser(id) {
            fetch(`edit_user.php?id=${id}`)
                .then(response => response.json())
                .then(user => {
                    if (user.success === false) {
                        alert(user.message);
                        return;
                    }
                    document.getElementById('edit-user-form').elements['id'].value = user.id;
                    document.getElementById('edit-user-form').elements['username'].value = user.username;
                    document.getElementById('edit-user-form').elements['email'].value = user.email;
                    document.getElementById('edit-user-form').elements['user_type'].value = user.user_type;
                    document.getElementById('edit-user-modal').style.display = 'block';
                });
        }

        function deleteUser(id) {
            if (confirm('Tem certeza?')) {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadAdminData();
                });
            }
        }

        function deleteEvent(id) {
            if (confirm('Tem certeza?')) {
                fetch('delete_event.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadAdminData();
                });
            }
        }

        document.getElementById('edit-event-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('edit_event.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                closeEditModal();
                loadAdminData();
            });
        });

        document.getElementById('edit-user-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('edit_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                closeEditUserModal();
                loadAdminData();
            });
        });

        loadAdminData();
    </script>
</body>
</html>
