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
    id              INT AUTO_INCREMENT PRIMARY KEY,
    manager_id      INT NOT NULL,
    nombre          VARCHAR(255) NOT NULL,
    deporte         ENUM('Fútbol','Baloncesto','Tenis','Pádel','Golf') NOT NULL,
    descripcion     TEXT,
    fecha           DATE NOT NULL,
    hora            TIME NOT NULL,
    plazas_totales  INT NOT NULL,
    calle           VARCHAR(255) NOT NULL,
    numero          VARCHAR(20) NOT NULL,
    cp              CHAR(5) NOT NULL,
    google_maps     VARCHAR(500),
    foto            VARCHAR(300),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (manager_id) REFERENCES usuarios(id) ON DELETE CASCADE
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
 