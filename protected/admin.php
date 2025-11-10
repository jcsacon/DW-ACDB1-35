<?php
/**
 * Panel de Administración - Página Protegida
 * Solo accesible para usuarios con rol de administrador
 */

require_once '../config/database.php';
require_once '../config/sesion.php';

iniciarSesionSegura();
requerirAdmin(); // Solo administradores pueden acceder

$usuario = obtenerUsuarioActual();
$exito = false;
$errores = [];
$mensaje = '';

// Obtener todos los usuarios
$conexion = conectarDB();

// Obtener lista de usuarios
$query = "SELECT id, nombre, correo, rol, estado, fecha_registro, ultimo_acceso FROM usuarios ORDER BY id DESC";
$result = mysqli_query($conexion, $query);
if (!$result) {
    error_log("Error al obtener usuarios: " . mysqli_error($conexion));
    $errores[] = "Error al cargar los usuarios";
} else {
    $usuarios = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Estadísticas
$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios");
$totalUsuarios = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'");
$usuariosActivos = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'administrador'");
$administradores = mysqli_fetch_assoc($result)['total'];

// Cambiar estado de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $usuarioId = intval($_POST['usuario_id']);
    $nuevoEstado = $_POST['nuevo_estado'] === 'activo' ? 'activo' : 'inactivo';
    
    if ($usuarioId === obtenerUsuarioId()) {
        $errores[] = "No puedes cambiar tu propio estado";
    } else {
        $conexion = conectarDB();
        
        $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
        $nuevoEstado_escaped = mysqli_real_escape_string($conexion, $nuevoEstado);
        
        $query = "UPDATE usuarios SET estado = '$nuevoEstado_escaped' WHERE id = '$usuarioId_escaped'";
        if (mysqli_query($conexion, $query)) {
            $exito = true;
            $mensaje = "Estado del usuario actualizado correctamente";
            header("Location: admin.php?success=1");
            exit();
        } else {
            error_log("Error al cambiar estado: " . mysqli_error($conexion));
            $errores[] = "Error al cambiar el estado del usuario";
        }
    }
}

// Cambiar rol de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_rol'])) {
    $usuarioId = intval($_POST['usuario_id']);
    $nuevoRol = $_POST['nuevo_rol'] === 'administrador' ? 'administrador' : 'usuario';
    
    if ($usuarioId === obtenerUsuarioId()) {
        $errores[] = "No puedes cambiar tu propio rol";
    } else {
        $conexion = conectarDB();
        
        $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
        $nuevoRol_escaped = mysqli_real_escape_string($conexion, $nuevoRol);
        
        $query = "UPDATE usuarios SET rol = '$nuevoRol_escaped' WHERE id = '$usuarioId_escaped'";
        if (mysqli_query($conexion, $query)) {
            $exito = true;
            $mensaje = "Rol del usuario actualizado correctamente";
            header("Location: admin.php?success=2");
            exit();
        } else {
            error_log("Error al cambiar rol: " . mysqli_error($conexion));
            $errores[] = "Error al cambiar el rol del usuario";
        }
    }
}

// Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $usuarioId = intval($_POST['usuario_id']);
    
    if ($usuarioId === obtenerUsuarioId()) {
        $errores[] = "No puedes eliminar tu propia cuenta";
    } else {
        $conexion = conectarDB();
        
        $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
        
        $query = "DELETE FROM usuarios WHERE id = '$usuarioId_escaped'";
        if (mysqli_query($conexion, $query)) {
            $exito = true;
            $mensaje = "Usuario eliminado correctamente";
            header("Location: admin.php?success=3");
            exit();
        } else {
            error_log("Error al eliminar usuario: " . mysqli_error($conexion));
            $errores[] = "Error al eliminar el usuario";
        }
    }
}

// Mensajes de éxito de redirección
if (isset($_GET['success'])) {
    $exito = true;
    switch ($_GET['success']) {
        case '1': $mensaje = "Estado del usuario actualizado correctamente"; break;
        case '2': $mensaje = "Rol del usuario actualizado correctamente"; break;
        case '3': $mensaje = "Usuario eliminado correctamente"; break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Sistema</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/admin.css">
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
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="profile.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>
                <a href="admin.php" class="nav-item active">
                    <i class="fas fa-users-cog"></i>
                    <span>Administración</span>
                </a>
            </div>
            <div class="navbar-user">
                <button class="theme-toggle" id="theme-toggle" aria-label="Cambiar tema">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                    <span class="user-role">Administrador</span>
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
            <div class="page-header">
                <h1><i class="fas fa-users-cog"></i> Panel de Administración</h1>
                <p>Gestiona los usuarios del sistema</p>
            </div>

            <?php if ($exito): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>¡Éxito!</strong>
                        <p><?php echo htmlspecialchars($mensaje); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Errores:</strong>
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Estadísticas -->
            <div class="info-cards">
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Usuarios</h3>
                        <p class="card-value"><?php echo $totalUsuarios; ?></p>
                        <p class="card-label">Usuarios registrados</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Usuarios Activos</h3>
                        <p class="card-value"><?php echo $usuariosActivos; ?></p>
                        <p class="card-label">Cuentas activas</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="card-content">
                        <h3>Administradores</h3>
                        <p class="card-value"><?php echo $administradores; ?></p>
                        <p class="card-label">Con permisos de admin</p>
                    </div>
                </div>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> Lista de Usuarios</h2>
                </div>
                
                <div class="table-responsive">
                    <table class="users-table">
                        <thead>
                            <tr>
                                
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th>Último Acceso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($user['correo']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['rol'] === 'administrador' ? 'admin' : 'user'; ?>">
                                        <?php echo ucfirst($user['rol']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $user['estado'] === 'activo' ? 'active' : 'inactive'; ?>">
                                        <?php echo ucfirst($user['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></td>
                                <td><?php echo $user['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($user['ultimo_acceso'])) : 'Nunca'; ?></td>
                                <td class="actions-cell">
                                    <?php if ($user['id'] !== obtenerUsuarioId()): ?>
                                        <!-- Cambiar Estado -->
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Cambiar el estado de este usuario?');">
                                            <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="nuevo_estado" value="<?php echo $user['estado'] === 'activo' ? 'inactivo' : 'activo'; ?>">
                                            <button type="submit" name="cambiar_estado" class="btn-icon" title="Cambiar estado">
                                                <i class="fas fa-<?php echo $user['estado'] === 'activo' ? 'ban' : 'check'; ?>"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Cambiar Rol -->
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Cambiar el rol de este usuario?');">
                                            <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="nuevo_rol" value="<?php echo $user['rol'] === 'usuario' ? 'administrador' : 'usuario'; ?>">
                                            <button type="submit" name="cambiar_rol" class="btn-icon" title="Cambiar rol">
                                                <i class="fas fa-user-<?php echo $user['rol'] === 'usuario' ? 'shield' : 'circle'; ?>"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Eliminar -->
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿ELIMINAR este usuario? Esta acción no se puede deshacer.');">
                                            <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="eliminar_usuario" class="btn-icon btn-danger" title="Eliminar usuario">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge badge-info">Tú</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- llamados de js para el dashboard -->
    <script src="../js/config.js"></script>
    <script src="../js/notifications.js"></script>
    <script src="../js/theme.js"></script>
    <script src="../js/dashboard.js"></script>
</body>
</html>
