-- Active: 1748917268798@@127.0.0.1@3306@project_full_db
-- Active: 1748917268798@@127.0.0.1@3306@project_full_db
CREATE DATABASE IF NOT EXISTS project_auth_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE project_auth_db;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) DEFAULT 'default-profile-pic.jpg',
    user_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);