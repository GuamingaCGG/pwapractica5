DROP TABLE IF EXISTS tareas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    rol_id INT AUTO_INCREMENT PRIMARY KEY,
    rol_nombre VARCHAR(50) NOT NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(rol_id) ON DELETE CASCADE
);

CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('Pendiente', 'En proceso', 'Completado') DEFAULT 'Pendiente',
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

INSERT INTO roles (rol_id, rol_nombre) VALUES 
(1, 'Administrador'),
(2, 'Gerente de proyecto'),
(3, 'Miembro del equipo');

INSERT INTO usuarios (nombre, email, contraseña, rol_id) VALUES 
('Carlos Administrador', 'admin@correo.com', '123456', 1),
('Maria Gerente', 'gerente@correo.com', '123456', 2),
('Juan Miembro', 'miembro@correo.com', '123456', 3);