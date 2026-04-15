-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS eventsportsbcn;
USE eventsportsbcn;

-- Tabla de usuarios (usuario normal y manager)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'manager') NOT NULL DEFAULT 'usuario',
    -- Solo para managers
    entidad VARCHAR(100) DEFAULT NULL,
    telefono VARCHAR(15) DEFAULT NULL,
    -- Foto de perfil
    foto_perfil VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de eventos deportivos
CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    deporte VARCHAR(50) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    ubicacion VARCHAR(150) NOT NULL,
    plazas_totales INT NOT NULL,
    plazas_disponibles INT NOT NULL,
    id_manager INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_manager) REFERENCES usuarios(id)
);

-- Tabla de inscripciones (usuario se apunta a un evento)
CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_evento INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_evento) REFERENCES eventos(id)
);
 