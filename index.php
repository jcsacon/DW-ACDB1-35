<?php
/**
 * Página Principal - Sistema de Login
 * Redirige al dashboard si ya está autenticado
 */

require_once 'config/database.php';
require_once 'config/sesion.php';

iniciarSesionSegura();

// Si ya está autenticado, redireccionar al dashboard
if (estaAutenticado()) {
    redireccionar('protected/dashboard.php');
}

$errores = [];
$correo_ingresado = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = sanitizar($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $correo_ingresado = $correo;
    
    // Validaciones básicas
    if (empty($correo)) {
        $errores[] = "El correo electrónico es obligatorio";
    }
    
    if (empty($contrasena)) {
        $errores[] = "La contraseña es obligatoria";
    }
    
    // Si no hay errores, verificar credenciales
    if (empty($errores)) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT id, nombre, correo, contrasena, rol, estado FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch();
            
            if ($usuario) {
                // Verificar estado del usuario
                if ($usuario['estado'] !== 'activo') {
                    $errores[] = "Esta cuenta está inactiva. Contacta al administrador.";
                }
                // Verificar contraseña
                elseif (password_verify($contrasena, $usuario['contrasena'])) {
                    // Login exitoso
                    iniciarSesionUsuario($usuario);
                    redireccionar('protected/dashboard.php');
                } else {
                    $errores[] = "Credenciales incorrectas. Por favor verifica tu correo y contraseña e intenta nuevamente.";
                }
            } else {
                $errores[] = "Credenciales incorrectas. Por favor verifica tu correo y contraseña e intenta nuevamente.";
            }
        } catch(PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            $errores[] = "Error en el servidor. Por favor, intente más tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h1>Sistema de Ingreso</h1>
                <p>Inicia sesión para acceder al sistema</p>
            </div>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Error de autenticación:</strong>
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="correo">
                        <i class="fas fa-envelope"></i>
                        Correo electrónico
                    </label>
                    <input 
                        type="email" 
                        id="correo" 
                        name="correo" 
                        placeholder="correo@ejemplo.com"
                        value="<?php echo htmlspecialchars($correo_ingresado); ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="contrasena">
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <input 
                        type="password" 
                        id="contrasena" 
                        name="contrasena" 
                        placeholder="Ingresa tu contraseña"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="auth-footer">
                <p>¿No tienes cuenta? <a href="auth/register.php">Regístrate aquí</a></p>
            </div>
        </div>

        <div class="auth-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>
    </div>

    <script src="js/config.js"></script>
    <script src="js/notifications.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>
