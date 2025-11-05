-- Crear base de datos
CREATE DATABASE IF NOT EXISTS portafolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portafolio_db;

-- Eliminar tabla si existe (para desarrollo)
DROP TABLE IF EXISTS usuarios;

-- Crear tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL DEFAULT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    rol ENUM('usuario', 'administrador') DEFAULT 'usuario',
    INDEX idx_correo (correo),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nota: En producción, las contraseñas deben ser hasheadas con password_hash(contrasena, admin123)
INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES
('Jhon Sacon', 'jhon@correo.com', '$2y$12$hrEJLKVQlfQv4RWggGFIIOSkAjLZpjAgPbmMzVmCVVoApI/bclFu6', 'administrador'),

-- Verificar la creación
SELECT 'Base de datos creada exitosamente' AS mensaje;
SELECT * FROM usuarios;

-- =====================================================
-- NOTAS IMPORTANTES:
-- =====================================================
-- 2. Las contraseñas están hasheadas con bcrypt (algoritmo seguro)
-- 3. Para cambiar las contraseñas, usar password_hash() en PHP
-- 4. El campo 'rol' permite diferenciar entre usuarios normales y administradores
-- 5. El campo 'estado' permite activar/desactivar usuarios
-- =====================================================
