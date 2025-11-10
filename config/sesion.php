<?php

/**
 * Iniciar sesión
 */
function iniciarSesionSegura() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();
        // Regenerar ID de sesión para prevenir fijación de sesión
        if (!isset($_SESSION['iniciada'])) {
            session_regenerate_id(true);
            $_SESSION['iniciada'] = true;
        }
    }
}

/**
 * Verificar si el usuario está autenticado
 * @return bool
 */
function estaAutenticado() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Obtener ID del usuario actual
 * @return int|null
 */
function obtenerUsuarioId() {
    return isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
}

/**
 * Obtener nombre del usuario actual
 * @return string|null
 */
function obtenerUsuarioNombre() {
    return isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : null;
}

/**
 * Obtener correo del usuario actual
 * @return string|null
 */
function obtenerUsuarioCorreo() {
    return isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : null;
}

/**
 * Obtener rol del usuario actual
 * @return string|null
 */
function obtenerUsuarioRol() {
    return isset($_SESSION['usuario_rol']) ? $_SESSION['usuario_rol'] : null;
}

/**
 * Verificar si el usuario es administrador
 * @return bool
 */
function esAdministrador() {
    return obtenerUsuarioRol() === 'administrador';
}

/**
 * Iniciar sesión de usuario
 * @param array $usuario - Datos del usuario
 */
function iniciarSesionUsuario($usuario) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    $_SESSION['usuario_correo'] = $usuario['correo'];
    $_SESSION['usuario_rol'] = $usuario['rol'];
    $_SESSION['tiempo_inicio'] = time();
    
    // Actualizar último acceso en la base de datos
    actualizarUltimoAcceso($usuario['id']);
}

/**
 * Cerrar sesión de usuario
 */
function cerrarSesion() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Limpiar variables de sesión
        $_SESSION = array();
        
        // Eliminar cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        // Destruir sesión
        session_destroy();
    }
}

/**
 * Verificar expiración de sesión
 * @return bool
 */
function verificarExpiracionSesion() {
    if (isset($_SESSION['tiempo_inicio'])) {
        $tiempoTranscurrido = time() - $_SESSION['tiempo_inicio'];
        // Verificar si ha excedido el tiempo de vida de la sesión
        if ($tiempoTranscurrido > SESSION_LIFETIME) {
            cerrarSesion();
            return true;
        }
    }
    return false;
}

/**
 * Requerir autenticación (proteger páginas)
 * @param string $redireccion - URL a redireccionar si no está autenticado
 */
function requerirAutenticacion($redireccion = '../index.php') {
    if (!estaAutenticado() || verificarExpiracionSesion()) {
        redireccionar($redireccion);
    }
}

/**
 * Requerir rol de administrador
 * @param string $redireccion - URL a redireccionar si no es admin
 */
function requerirAdmin($redireccion = '../protected/dashboard.php') {
    requerirAutenticacion();
    
    if (!esAdministrador()) {
        redireccionar($redireccion);
    }
}

/**
 * Actualizar último acceso del usuario
 * @param int $usuarioId
 */
function actualizarUltimoAcceso($usuarioId) {
    $conexion = conectarDB();
    
    $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
    $query = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = '$usuarioId_escaped'";
    
    if (!mysqli_query($conexion, $query)) {
        error_log("Error al actualizar último acceso: " . mysqli_error($conexion));
    }
}

/**
 * Obtener información completa del usuario actual
 * @return array|null
 */
function obtenerUsuarioActual() {
    if (!estaAutenticado()) {
        return null;
    }
    
    $conexion = conectarDB();
    
    $usuarioId = obtenerUsuarioId();
    $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
    // Obtener datos del usuario desde la base de datos
    $query = "SELECT id, nombre, correo, rol, fecha_registro, ultimo_acceso FROM usuarios WHERE id = '$usuarioId_escaped' AND estado = 'activo'";
    $result = mysqli_query($conexion, $query);
    
    if (!$result) {
        error_log("Error al obtener usuario actual: " . mysqli_error($conexion));
        return null;
    }
    return mysqli_fetch_assoc($result);
}
?>