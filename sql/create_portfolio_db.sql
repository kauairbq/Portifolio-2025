CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT,
    creation_date DATE,
    FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
);

-- Insert sample categories
INSERT INTO
    categories (name)
VALUES ('Web Development'),
    ('Mobile Apps'),
    ('Data Science');

-- Insert sample projects
INSERT INTO
    projects (
        title,
        description,
        category_id,
        creation_date
    )
VALUES (
        'Portfolio Website',
        'A personal portfolio website.',
        1,
        '2023-01-15'
    ),
    (
        'Weather App',
        'A mobile app for weather forecasts.',
        2,
        '2023-02-20'
    ),
    (
        'Sales Analysis',
        'Data science project analyzing sales data.',
        3,
        '2023-03-10'
    );