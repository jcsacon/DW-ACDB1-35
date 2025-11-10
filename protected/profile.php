<?php
/**
 * Perfil de Usuario - Página Protegida
 * Sistema de Autenticación
 */

require_once '../config/database.php';
require_once '../config/sesion.php';

iniciarSesionSegura();
requerirAutenticacion();

$usuario = obtenerUsuarioActual();
$exito = false;
$errores = [];

// Procesar actualización de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_perfil'])) {
    $nombre = sanitizar($_POST['nombre'] ?? '');
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    } elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }
    
    if (empty($errores)) {
        $conexion = conectarDB();
        
        $nombre_escaped = mysqli_real_escape_string($conexion, $nombre);
        $usuarioId = obtenerUsuarioId();
        $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
        
        $query = "UPDATE usuarios SET nombre = '$nombre_escaped' WHERE id = '$usuarioId_escaped'";
        if (mysqli_query($conexion, $query)) {
            $_SESSION['usuario_nombre'] = $nombre;
            $usuario['nombre'] = $nombre;
            $exito = true;
        } else {
            error_log("Error al actualizar perfil: " . mysqli_error($conn));
            $errores[] = "Error al actualizar el perfil";
        }
    }
}

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_contrasena'])) {
    $contrasena_actual = $_POST['contrasena_actual'] ?? '';
    $contrasena_nueva = $_POST['contrasena_nueva'] ?? '';
    $confirmar_nueva = $_POST['confirmar_nueva'] ?? '';
    
    if (empty($contrasena_actual) || empty($contrasena_nueva) || empty($confirmar_nueva)) {
        $errores[] = "Todos los campos de contraseña son obligatorios";
    } elseif (strlen($contrasena_nueva) < 6) {
        $errores[] = "La nueva contraseña debe tener al menos 6 caracteres";
    } elseif ($contrasena_nueva !== $confirmar_nueva) {
        $errores[] = "Las contraseñas nuevas no coinciden";
    } else {
        $conexion = conectarDB();
        
        $usuarioId = obtenerUsuarioId();
        $usuarioId_escaped = mysqli_real_escape_string($conexion, $usuarioId);
        
        $query = "SELECT contrasena FROM usuarios WHERE id = '$usuarioId_escaped'";
        $result = mysqli_query($conexion, $query);
        
        if (!$result) {
            error_log("Error al verificar contraseña: " . mysqli_error($conexion));
            $errores[] = "Error en el servidor";
        } else {
            $usuario_db = mysqli_fetch_assoc($result);
            
            if (password_verify($contrasena_actual, $usuario_db['contrasena'])) {
                $contrasena_hash = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
                $contrasena_hash_escaped = mysqli_real_escape_string($conexion, $contrasena_hash);
                
                $query = "UPDATE usuarios SET contrasena = '$contrasena_hash_escaped' WHERE id = '$usuarioId_escaped'";
                if (mysqli_query($conexion, $query)) {
                    $exito = true;
                } else {
                    error_log("Error al cambiar contraseña: " . mysqli_error($conexion));
                    $errores[] = "Error al cambiar la contraseña";
                }
            } else {
                $errores[] = "La contraseña actual es incorrecta";
            }
        }
    }
}
?>
<!-- inicio del html -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
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
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="profile.php" class="nav-item active">
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
                    <span class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                    <span class="user-role"><?php echo ucfirst($usuario['rol']); ?></span>
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
                <h1><i class="fas fa-user-circle"></i> Mi Perfil</h1>
                <p>Administra tu información personal y contraseña</p>
            </div>

            <?php if ($exito): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                        <strong>¡Actualización exitosa!</strong>
                        <p>Tus cambios han sido guardados correctamente.</p>
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

            <div class="profile-grid">
                <!-- Información de Perfil -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2><i class="fas fa-user"></i> Información Personal</h2>
                    </div>
                    <form method="POST" action="" class="profile-form">
                        <div class="form-group">
                            <label for="nombre">Nombre completo</label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="correo">Correo electrónico</label>
                            <input 
                                type="email" 
                                id="correo" 
                                name="correo" 
                                value="<?php echo htmlspecialchars($usuario['correo']); ?>"
                                disabled
                            >
                            <small> <i class="fas fa-warning"></i> <em>El correo no puede ser modificado</em> </small>
                        </div>

                        <div class="form-group">
                            <label>Rol</label>
                            <input 
                                type="text" 
                                value="<?php echo ucfirst($usuario['rol']); ?>"
                                disabled
                            >
                        </div>

                        <button type="submit" name="actualizar_perfil" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Guardar Cambios
                        </button>
                    </form>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2><i class="fas fa-lock"></i> Cambiar Contraseña</h2>
                    </div>
                    <form method="POST" action="" class="profile-form">
                        <div class="form-group">
                            <label for="contrasena_actual">Contraseña actual</label>
                            <input 
                                type="password" 
                                id="contrasena_actual" 
                                name="contrasena_actual" 
                                placeholder="Ingresa tu contraseña actual"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="contrasena_nueva">Nueva contraseña</label>
                            <input 
                                type="password" 
                                id="contrasena_nueva" 
                                name="contrasena_nueva" 
                                placeholder="Mínimo 6 caracteres"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="confirmar_nueva">Confirmar nueva contraseña</label>
                            <input 
                                type="password" 
                                id="confirmar_nueva" 
                                name="confirmar_nueva" 
                                placeholder="Confirma tu nueva contraseña"
                                required
                            >
                        </div>

                        <button type="submit" name="cambiar_contrasena" class="btn btn-primary">
                            <i class="fas fa-key"></i>
                            Cambiar Contraseña
                        </button>
                    </form>
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
