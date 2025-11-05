<?php
/**
 * Configuración de Conexión a Base de Datos
 * Sistema de Autenticación
 * 
 * @author Jhon Cristhian Sacon
 * @version 1.0
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'portafolio_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de sesiones
define('SESSION_NAME', 'PORTAFOLIO_SESSION');
define('SESSION_LIFETIME', 300); // 5 minutos en segundos

// Configuración de seguridad
define('HASH_ALGORITHM', PASSWORD_BCRYPT);
define('HASH_COST', 12);

/**
 * Clase para gestionar la conexión a la base de datos
 */
class Database {
    private static $instance = null;
    private $conexion;
    
    /**
     * Constructor privado para patrón Singleton
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            $this->conexion = new PDO($dsn, DB_USER, DB_PASS, $opciones);
            
        } catch(PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die("Error al conectar con la base de datos. Por favor, contacte al administrador.");
        }
    }
    
    /**
     * Obtener instancia única de la conexión
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Obtener la conexión PDO
     * 
     * @return PDO
     */
    public function getConexion() {
        return $this->conexion;
    }
    
    /**
     * Prevenir clonación del objeto
     */
    private function __clone() {}
    
    /**
     * Prevenir deserialización del objeto
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar la instancia de Database");
    }
}

/**
 * Función auxiliar para obtener la conexión
 * 
 * @return PDO
 */
function getDB() {
    return Database::getInstance()->getConexion();
}

/**
 * Función para sanitizar datos de entrada
 * 
 * @param string $data
 * @return string
 */
function sanitizar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Función para validar correo electrónico
 * 
 * @param string $correo
 * @return bool
 */
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Función para redireccionar
 * 
 * @param string $url
 */
function redireccionar($url) {
    header("Location: $url");
    exit();
}

// Configurar zona horaria
date_default_timezone_set('America/Guayaquil');

// Configurar reporte de errores (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
