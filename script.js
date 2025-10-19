document.addEventListener('DOMContentLoaded', () => {
    // const contactForm = document.getElementById('contactForm');
    const servicosContainer = document.getElementById('servicos-container');
    const servicoDetalhes = document.getElementById('servico-detalhes');

    // Function to close lightbox
    const closeLightbox = () => {
        document.getElementById('lightbox').style.display = 'none';
    };

    // Add event listener to close lightbox
    document.querySelector('.close-btn').addEventListener('click', closeLightbox);

    // Load certificates when the document is ready
    loadCertificates();

    const loadServices = async () => {
        console.log("Loading services..."); // Debugging log
        console.log("Fetching servicos.json..."); // Debugging log
        try {
            const response = await fetch('./servicos.json');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            servicosContainer.innerHTML = ''; // Clear previous services
            console.log("Services loaded:", data.servicos); // Debugging log
            if (!data.servicos || data.servicos.length === 0) {
                console.error("No services found in servicos.json.");
            }

            if (data.servicos) {
                data.servicos.forEach(servico => {
                    const item = document.createElement('a');
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.textContent = servico.titulo;
                    item.onclick = () => displayServiceDetails(servico);
                    servicosContainer.appendChild(item);
                });
            }
        } catch (error) {
            console.error('Error loading services:', error);
        }
    };

    // Function to display service details
    const displayServiceDetails = (servico) => {
        servicoDetalhes.innerHTML = `
            <h3>${servico.titulo}</h3>
            <p>${servico.descricao}</p>
            <img src="${servico.imagem}" alt="${servico.titulo}" class="img-fluid">
        `;
    };

    // Load services when the document is ready
    loadServices();

    const formMessage = document.getElementById('form-message');
    const feedbackForm = document.getElementById('feedbackForm');
    const feedbackMessage = document.getElementById('feedback-message');

    // Function to handle form submission for Feedback Form
    const handleFeedbackSubmit = async (event) => {
        event.preventDefault();


        try {
            const response = await fetch('submit.php', { 
                method: 'POST',
                body: feedbackFormData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.text();
            feedbackMessage.textContent = data;
            feedbackForm.reset();
        } catch (error) {
            console.error('Error:', error);
            feedbackMessage.textContent = 'Ocorreu um erro ao enviar o feedback.';
        }
    };

    // Add event listener to the feedback form
    feedbackForm.addEventListener('submit', handleFeedbackSubmit);
});

document.getElementById('contactForm').addEventListener('submit', function (event) {
    event.preventDefault();
  
    // Get form values
    const name = document.getElementById('name').value;
    const birthDate = new Date(document.getElementById('birthDate').value);
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
  
    // Calculate age
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
  
    // Regular expressions
    const phoneRegex = /^\d{9}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
    // Validation
    if (!name || !birthDate || !phone || !email || !message) {
      alert('Todos os campos são obrigatórios.');
      return;
    }
    if (age < 18) {
      alert('Você deve ter mais de 18 anos para preencher este formulário.');
      return;
    }
    if (!phoneRegex.test(phone)) {
      alert('Por favor, insira um número de telefone válido (9 dígitos).');
      return;
    }
    if (!emailRegex.test(email)) {
      alert('Por favor, insira um endereço de e-mail válido.');
      return;
    }
  
    // Simulate form submission
    alert('Dados enviados com sucesso!');
    document.getElementById('contactForm').reset();
});
