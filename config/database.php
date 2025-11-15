<?php
/**
 * Configuración de Conexión a Base de Datos
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'portafolio_db');// Nombre de la base de datos

// Usuario MySQL para el Sistema de Login: 'sacon'
// Este usuario tiene privilegios limitados CRUD (SELECT, INSERT, UPDATE, DELETE)
// sobre la base de datos 'portafolio_db'
// NO tiene privilegios para crear, modificar o eliminar tablas.
define('DB_USER', 'sacon');// Usuario MySQL con privilegios limitados
define('DB_PASS', 'D3s4roll0w3b'); // Contraseña del usuario MySQL

/**
 * Configuración de Sesiones
 */

// Configuración de sesiones
define('SESSION_LIFETIME', 300); // 5 minutos en segundos
define('SESSION_NAME', 'session_id');

// Configuración de hash
define('HASH_ALGORITHM', PASSWORD_DEFAULT);
define('HASH_COST', 10);

/**
 * Conectar a la base de datos
 */
function conectarDB() {
    static $conexion = null;
    
    if ($conexion === null) {
        $conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (!$conexion) {
            die("Error al conectar con la base de datos: " . mysqli_connect_error());
        }
        mysqli_set_charset($conexion, 'utf8mb4');
    }
    return $conexion;
}

/**
 * Función básica para redireccionar
 */
function redireccionar($url) {
    header("Location: $url");
    exit();
}

/**
 * Función para sanitizar entradas
 */
function sanitizar($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
    return $dato;
}

/**
 * Función para validar correo electrónico
 */
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

// Configuración básica
date_default_timezone_set('America/Guayaquil');
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>