CREATE DATABASE IF NOT EXISTS webappdb;

USE webappdb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL
);

-- Example user (password: password123 hashed with MD5)
INSERT INTO users (username, password)
VALUES ('admin', MD5('password123'));
