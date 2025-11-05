<?php
/**
 * Dashboard - Página Protegida
 * Sistema de Autenticación
 */

require_once '../config/database.php';
require_once '../config/sesion.php';

iniciarSesionSegura();
requerirAutenticacion();

// Obtener información del usuario actual
$usuario = obtenerUsuarioActual();
$nombre = obtenerUsuarioNombre();
$correo = obtenerUsuarioCorreo();
$rol = obtenerUsuarioRol();

// Obtener estadísticas del usuario
try {
    $db = getDB();
    
    // Contar total de usuarios (solo si es admin)
    $totalUsuarios = 0;
    if (esAdministrador()) {
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch();
        $totalUsuarios = $result['total'];
    }
    
} catch(PDOException $e) {
    error_log("Error al obtener estadísticas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar del Dashboard -->
    <nav class="dashboard-navbar">
        <div class="navbar-content">
            <div class="navbar-brand">
                <i class="fas fa-briefcase"></i>
                <span>Panel de Navegación</span>
            </div>
            <div class="navbar-menu">
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="profile.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>
                <?php if (esAdministrador()): ?>
                <a href="admin.php" class="nav-item">
                    <i class="fas fa-users-cog"></i>
                    <span>Administración</span>
                </a>
                <?php endif; ?>
            </div>
            <div class="navbar-user">
                <button class="theme-toggle" id="theme-toggle" aria-label="Cambiar tema">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($nombre); ?></span>
                    <span class="user-role"><b>ROL:</b> <em><?php echo ucfirst($rol); ?></em></span>
                </div>
                <a href="../auth/logout.php" class="btn-logout" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="dashboard-container">
        <div class="dashboard-content">
            <!-- Encabezado de Bienvenida -->
            <div class="welcome-header">
                <div class="welcome-text">
                    <h1>¡Bienvenido, <?php echo htmlspecialchars(explode(' ', $nombre)[0]); ?>! 👋</h1>
                    <p>Estás en tu panel de control</p>
                </div>
            </div>

            <!-- Tarjetas de Información -->
            <div class="info-cards">
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Tu Cuenta</h3>
                        <p class="card-value"><?php echo ucfirst($rol); ?></p>
                        <p class="card-label">Tipo de usuario</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="card-content">
                        <h3>Correo</h3>
                        <h5><?php echo htmlspecialchars($correo); ?></h5>
                        <p class="card-label">Tu email registrado</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-content">
                        <h3>Miembro desde</h3>
                        <p class="card-value"><?php echo date('D,  d/M/Y', strtotime($usuario['fecha_registro'])); ?></p>
                        <p class="card-label">Fecha de registro</p>
                    </div>
                </div>

                <?php if (esAdministrador()): ?>
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Usuarios</h3>
                        <p class="card-value"><?php echo $totalUsuarios; ?></p>
                        <p class="card-label">Total de usuarios</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sección de Acciones Rápidas -->
            <div class="quick-actions">
                <h2>Acciones Rápidas</h2>
                <div class="actions-grid">
                    <a href="profile.php" class="action-card">
                        <i class="fas fa-user-edit"></i>
                        <h3>Editar Perfil</h3>
                        <p>Actualiza tu información personal</p>
                    </a>

                    <?php if (esAdministrador()): ?>
                    <a href="admin.php" class="action-card">
                        <i class="fas fa-cog"></i>
                        <h3>Administración</h3>
                        <p>Gestiona usuarios del sistema</p>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Información de Sesión -->
            <div class="session-info">
                <div class="session-card">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h4>Información de Sesión</h4>
                        <p><strong>Último acceso:</strong> <?php echo $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Primer acceso'; ?></p>
                        <p><strong>Duración de la sesión:</strong> <?php echo SESSION_LIFETIME/60; ?> minutos</p>
                        <p><strong>Estado:</strong> <span class="status-active">Activo</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/config.js"></script>
    <script src="../js/notifications.js"></script>
    <script src="../js/theme.js"></script>
    <script src="../js/dashboard.js"></script>
</body>
</html>
