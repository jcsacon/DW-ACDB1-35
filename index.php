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

// --- INICIO: Lógica de control de intentos de login y bloqueo ---
require_once 'captcha.php';

define('MAX_LOGIN_ATTEMPTS', 5); //intentos maximos antes del bloqueo
define('LOCKOUT_TIME_SECONDS', 60); //tiempo de bloqueo en segundos

// Función para manejar el fallo de login
function manejarFalloLogin(&$errores) {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }
    // Verificar si se ha alcanzado el límite de intentos
    if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $_SESSION['lockout_time'] = time() + LOCKOUT_TIME_SECONDS;
        $errores[] = "Has excedido el número de intentos. Tu cuenta ha sido bloqueada por " . LOCKOUT_TIME_SECONDS . " segundos.";
        unset($_SESSION['login_attempts']); // Limpiar para el próximo ciclo de bloqueo
        limpiarCaptcha();
    } else {
        // Calcular intentos restantes
        $intentos_restantes = MAX_LOGIN_ATTEMPTS - $_SESSION['login_attempts'];
        $errores[] = "Credenciales incorrectas. Te quedan {$intentos_restantes} intentos.";
    }
}

// Verificar si el usuario está bloqueado
if (isset($_SESSION['lockout_time']) && $_SESSION['lockout_time'] > time()) {
    $tiempo_restante = $_SESSION['lockout_time'] - time();
    $errores[] = "Has excedido el número de intentos. Por favor, espera {$tiempo_restante} segundos antes de volver a intentarlo.";
}
// --- FIN: Lógica de control de intentos de login y bloqueo ---

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_SESSION['lockout_time']) || $_SESSION['lockout_time'] <= time())) {
    // Si el bloqueo ha expirado, limpiar las variables
    if (isset($_SESSION['lockout_time']) && $_SESSION['lockout_time'] <= time()) {
        unset($_SESSION['lockout_time']);
        unset($_SESSION['login_attempts']);
        unset($_SESSION['captcha_question']);
        unset($_SESSION['captcha_answer']);
    }
    // Obtener y sanitizar datos
    $correo = sanitizar($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $correo_ingresado = $correo;
    
    // Validaciones básicas
    if (empty($correo)) $errores[] = "El correo electrónico es obligatorio";
    if (empty($contrasena)) $errores[] = "La contraseña es obligatoria";
    
    // Validación de Captcha
    if (debeMostrarCaptcha()) {
        $captcha_ingresado = $_POST['captcha'] ?? '';
        if (!verificarCaptcha($captcha_ingresado)) {
            $errores[] = "El resultado de la operación matemática es incorrecto.";
        }
    }

    // Si no hay errores de validación, verificar credenciales
    if (empty($errores)) {
        $conexion = conectarDB();
        $correo_escaped = mysqli_real_escape_string($conexion, $correo);
        
        $query = "SELECT id, nombre, correo, contrasena, rol, estado FROM usuarios WHERE correo = '$correo_escaped'";
        $result = mysqli_query($conexion, $query);
        
        if (!$result) {
            error_log("Error en login: " . mysqli_error($conexion));
            $errores[] = "Error en el servidor. Por favor, intente más tarde.";
        } else {
            $usuario = mysqli_fetch_assoc($result);
            
            if ($usuario && $usuario['estado'] === 'activo' && password_verify($contrasena, $usuario['contrasena'])) {
                // Login exitoso
                unset($_SESSION['login_attempts']);
                unset($_SESSION['lockout_time']);
                limpiarCaptcha();
                iniciarSesionUsuario($usuario);
                redireccionar('protected/dashboard.php');
            } else {
                if ($usuario && $usuario['estado'] !== 'activo') {
                     $errores[] = "Esta cuenta está inactiva. Contacta al administrador.";
                } else {
                    manejarFalloLogin($errores);
                }
            }
        }
    }
}

// Manejar la lógica del CAPTCHA
$mostrar_captcha = debeMostrarCaptcha();
manejarCaptcha();
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
            <! -- Mostrar errores en caso de haberlos en el login -->
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
            <! -- Mostrar formulario de login -->
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
                <! -- Mostrar Captcha si es necesario -->
                <?php if ($mostrar_captcha): ?>
                <div class="form-group captcha-group">
                    <label for="captcha">
                        <i class="fas fa-shield-alt"></i>
                        Verificación de seguridad: Resuelve la operación
                    </label>
                    <div class="captcha-container">
                        <span class="captcha-text"><?php echo htmlspecialchars($_SESSION['captcha_question']); ?></span>
                        <input
                            type="text"
                            id="captcha"
                            name="captcha"
                            placeholder="Resultado"
                            required
                            pattern="\d{1,2}"
                            title="Ingresa el resultado de la operación."
                        >
                    </div>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="auth-footer">
                <p>¿No tienes cuenta? <a href="auth/register.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
    <! -- Scripts -->
    <script src="js/config.js"></script>
    <script src="js/notifications.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>
