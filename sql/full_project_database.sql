CREATE DATABASE IF NOT EXISTS project_full_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE project_full_db;

-- Users table for authentication
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) DEFAULT 'default-profile-pic.jpg',
    user_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events table
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE,
    time TIME,
    venue VARCHAR(255),
    capacity INT,
    price DECIMAL(10, 2) NOT NULL
);

-- Tickets table
CREATE TABLE tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT,
    price DECIMAL(10, 2),
    seat_number VARCHAR(50),
    FOREIGN KEY (event_id) REFERENCES events (id)
);

-- Customers table
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50)
);

-- Sales table
CREATE TABLE sales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT,
    sale_date DATETIME,
    customer_id INT,
    FOREIGN KEY (ticket_id) REFERENCES tickets (id),
    FOREIGN KEY (customer_id) REFERENCES customers (id)
);

-- Views for reporting
CREATE VIEW event_details AS
SELECT
    e.id AS event_id,
    e.name AS event_name,
    e.description,
    e.date,
    e.time,
    e.venue,
    e.capacity,
    COUNT(t.id) AS total_tickets,
    SUM(
        CASE
            WHEN s.id IS NOT NULL THEN 1
            ELSE 0
        END
    ) AS sold_tickets,
    (
        COUNT(t.id) - SUM(
            CASE
                WHEN s.id IS NOT NULL THEN 1
                ELSE 0
            END
        )
    ) AS available_tickets
FROM
    events e
    LEFT JOIN tickets t ON e.id = t.event_id
    LEFT JOIN sales s ON t.id = s.ticket_id
GROUP BY
    e.id;

CREATE VIEW customer_sales_summary AS
SELECT
    c.id AS customer_id,
    c.name AS customer_name,
    COUNT(s.id) AS number_of_tickets_purchased,
    SUM(t.price) AS total_spent
FROM
    customers c
    LEFT JOIN sales s ON c.id = s.customer_id
    LEFT JOIN tickets t ON s.ticket_id = t.id
GROUP BY
    c.id;

CREATE VIEW event_sales_summary AS
SELECT
    e.id AS event_id,
    e.name AS event_name,
    COUNT(s.id) AS number_of_tickets_sold,
    SUM(t.price) AS total_revenue
FROM
    events e
    LEFT JOIN tickets t ON e.id = t.event_id
    LEFT JOIN sales s ON t.id = s.ticket_id
GROUP BY
    e.id;

CREATE VIEW tickets_status AS
SELECT
    t.id AS ticket_id,
    e.name AS event_name,
    t.price,
    t.seat_number,
    CASE
        WHEN s.id IS NOT NULL THEN 'Sold'
        ELSE 'Available'
    END AS status
FROM
    tickets t
    LEFT JOIN events e ON t.event_id = e.id
    LEFT JOIN sales s ON t.id = s.ticket_id;

-- Password resets table for recovery
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Cart table for shopping cart
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE
);

-- Purchases table for purchase history
CREATE TABLE purchases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE
);

-- Service requests table for portfolio service requests
CREATE TABLE service_requests (
    user_id INT NOT NULL,
    service_type VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Feedback table for portfolio feedback
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
