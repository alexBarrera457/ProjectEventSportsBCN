-- Create Database
CREATE DATABASE IF NOT EXISTS eventsportsbcn;
USE eventsportsbcn;

-- Create Table usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

-- Insert 3 test records
INSERT INTO usuarios (nombre_usuario, password_hash, email, estado) VALUES
('user1', '12345', 'user1@example.com', 'activo'),
('user2', '12345', 'user2@example.com', 'activo'),
('user3', '12345', 'user3@example.com', 'activo');
 