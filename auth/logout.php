<?php
/**
 * Página de Cierre de Sesión
 * Sistema de Autenticación
 */

require_once '../config/database.php';
require_once '../config/sesion.php';

iniciarSesionSegura();

// Cerrar sesión
cerrarSesion();

// Redireccionar al index.php (página de login principal)
header("Location: ../index.php?mensaje=sesion_cerrada");
exit();
?>
