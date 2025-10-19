-- Active: 1742922675811@@localhost@3306
CREATE DATABASE Portfolio2025;

USE Portfolio2025;

CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE,
    time TIME,
    venue VARCHAR(255),
    capacity INT
);

CREATE TABLE tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT,
    price DECIMAL(10, 2),
    seat_number VARCHAR(50),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE sales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT,
    sale_date DATETIME,
    customer_id INT,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50)
);


CREATE VIEW event_details AS
SELECT e.id AS event_id, e.name AS event_name, e.description, e.date, e.time, e.venue, e.capacity,
       COUNT(t.id) AS total_tickets,
       SUM(CASE WHEN s.id IS NOT NULL THEN 1 ELSE 0 END) AS sold_tickets,
       (COUNT(t.id) - SUM(CASE WHEN s.id IS NOT NULL THEN 1 ELSE 0 END)) AS available_tickets
FROM events e
LEFT JOIN tickets t ON e.id = t.event_id
LEFT JOIN sales s ON t.id = s.ticket_id
GROUP BY e.id;

CREATE VIEW customer_sales_summary AS
SELECT c.id AS customer_id, c.name AS customer_name,
       COUNT(s.id) AS number_of_tickets_purchased,
       SUM(t.price) AS total_spent
FROM customers c
LEFT JOIN sales s ON c.id = s.customer_id
LEFT JOIN tickets t ON s.ticket_id = t.id
GROUP BY c.id;

CREATE VIEW event_sales_summary AS
SELECT e.id AS event_id, e.name AS event_name,
       COUNT(s.id) AS number_of_tickets_sold,
       SUM(t.price) AS total_revenue
FROM events e
LEFT JOIN tickets t ON e.id = t.event_id
LEFT JOIN sales s ON t.id = s.ticket_id
GROUP BY e.id;

CREATE VIEW tickets_status AS
SELECT t.id AS ticket_id, e.name AS event_name, t.price, t.seat_number,
       CASE WHEN s.id IS NOT NULL THEN 'Sold' ELSE 'Available' END AS status
FROM tickets t
LEFT JOIN events e ON t.event_id = e.id
LEFT JOIN sales s ON t.id = s.ticket_id;
