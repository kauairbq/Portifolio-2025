import express from 'express';
import bodyParser from 'body-parser';
import cors from 'cors';
const app = express();
const port = 3000;

app.use(cors());

app.use(bodyParser.json());

let tasks = [
    { id: 1, title: 'Estudar JavaScript', description: 'Aprender sobre DOM manipulation' },
    { id: 2, title: 'Criar API REST', description: 'Usar Node.js e Express' },
    { id: 3, title: 'Tarefa Restaurada', description: 'Esta tarefa foi restaurada após exclusão acidental' },
    { id: 4, title: 'Aprender Python', description: 'Estudar sintaxe básica e estruturas de dados' },
    { id: 5, title: 'Desenvolver com React', description: 'Criar componentes e gerenciar estado' },
    { id: 6, title: 'Estudar SQL', description: 'Aprender consultas e modelagem de bancos de dados' },
    { id: 7, title: 'Implementar Segurança', description: 'Adicionar autenticação e proteção contra vulnerabilidades' },
    { id: 8, title: 'Dominar Git', description: 'Controle de versão e colaboração em projetos' }
];

let nextId = 9;

app.get('/api/tasks', (req, res) => {
    res.json(tasks);
});

app.post('/api/tasks', (req, res) => {
    const { title, description } = req.body;
    if (!title) {
        return res.status(400).json({ error: 'Title is required' });
    }

    const newTask = {
        id: nextId++,
        title,
        description
    };

    tasks.push(newTask);
    res.status(201).json(newTask);
});

app.put('/api/tasks/:id', (req, res) => {
    const taskId = parseInt(req.params.id);
    const { title, description } = req.body;
    const taskIndex = tasks.findIndex(task => task.id === taskId);

    if (taskIndex === -1) {
        return res.status(404).json({ error: 'Task not found' });
    }

    if (!title) {
        return res.status(400).json({ error: 'Title is required' });
    }

    tasks[taskIndex] = { id: taskId, title, description };
    res.json(tasks[taskIndex]);
});

app.delete('/api/tasks/:id', (req, res) => {
    const taskId = parseInt(req.params.id);
    tasks = tasks.filter(task => task.id !== taskId);
    res.status(204).send();
});

app.listen(port, () => {
    console.log(`Backend rodando em http://localhost:${port}`);
});