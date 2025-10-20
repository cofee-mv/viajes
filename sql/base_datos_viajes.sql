-- Crear base de datos
CREATE DATABASE IF NOT EXISTS viajes_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE viajes_db;

-- --------------------------------------------------
-- TABLA: roles
-- --------------------------------------------------
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

-- Insertar roles por defecto
INSERT INTO roles (nombre_rol) VALUES ('Administrador'), ('Empleado'), ('Cliente');

-- --------------------------------------------------
-- TABLA: usuarios
-- --------------------------------------------------
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id_rol) ON DELETE CASCADE
);

-- --------------------------------------------------
-- TABLA: viajes
-- --------------------------------------------------
CREATE TABLE viajes (
    id_viaje INT AUTO_INCREMENT PRIMARY KEY,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    fecha_salida DATE NOT NULL,
    fecha_regreso DATE,
    precio DECIMAL(10,2) NOT NULL
);

-- Insertar viajes de ejemplo
INSERT INTO viajes (origen, destino, fecha_salida, fecha_regreso, precio) VALUES
('Bogotá', 'Medellín', '2025-11-10', '2025-11-15', 150000),
('Cartagena', 'Santa Marta', '2025-12-01', '2025-12-05', 200000),
('Cali', 'Bogotá', '2025-10-20', '2025-10-22', 180000),
('Barranquilla', 'San Andrés', '2025-11-05', '2025-11-10', 350000);

-- --------------------------------------------------
-- TABLA: reservas
-- --------------------------------------------------
CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    viaje_id INT NOT NULL,
    num_pasajeros INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (viaje_id) REFERENCES viajes(id_viaje) ON DELETE CASCADE
);

-- --------------------------------------------------
-- VERIFICAR
-- --------------------------------------------------
-- Mostrar todas las tablas creadas
SHOW TABLES;

-- Mostrar estructura de las tablas
DESCRIBE usuarios;
DESCRIBE viajes;
DESCRIBE reservas;