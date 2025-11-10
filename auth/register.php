<?php
/**
 * Página de Registro de Usuarios
 */

require_once '../config/database.php';
require_once '../config/sesion.php';

iniciarSesionSegura();

// Si ya está autenticado, redireccionar al dashboard
if (estaAutenticado()) {
    redireccionar('../protected/dashboard.php');
}

$errores = [];
$exito = false;

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar datos
    $nombre = sanitizar($_POST['nombre'] ?? '');
    $correo = sanitizar($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    
    // Validaciones
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    } elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }

    if (empty($correo)) {
        $errores[] = "El correo electrónico es obligatorio";
    } elseif (!validarCorreo($correo)) {
        $errores[] = "El formato del correo electrónico no es válido";
    }
    
    if (empty($contrasena)) {
        $errores[] = "La contraseña es obligatoria";
    } elseif (strlen($contrasena) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if ($contrasena !== $confirmar_contrasena) {
        $errores[] = "Las contraseñas no coinciden";
    }
    
    // Si no hay errores, proceder con el registro
    if (empty($errores)) {
        $conexion = conectarDB();
        
        $nombre_escaped = mysqli_real_escape_string($conexion, $nombre);
        $correo_escaped = mysqli_real_escape_string($conexion, $correo);
        
        // Verificar si el correo ya existe
        $query = "SELECT id FROM usuarios WHERE correo = '$correo_escaped'";
        $result = mysqli_query($conexion, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $errores[] = "El correo electrónico ya está registrado";
        } else {
            // Hash de la contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $contrasena_hash_escaped = mysqli_real_escape_string($conexion, $contrasena_hash);
            
            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES ('$nombre_escaped', '$correo_escaped', '$contrasena_hash_escaped', 'usuario')";
            
            if (mysqli_query($conexion, $query)) {
                $exito = true;
            } else {
                error_log("Error en registro: " . mysqli_error($conexion));
                $errores[] = "Error al registrar el usuario. Intente nuevamente.";
            }
        }
    }
}
// Mostrar formulario de registro
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Crear Cuenta</h1>
                <p>Completa el formulario para registrarte</p>
            </div>
            <! -- Mostrar mensaje de éxito -->
            <?php if ($exito): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>¡Registro exitoso!</strong>
                        <p>Tu cuenta ha sido creada correctamente. Ahora puedes <a href="../index.php">iniciar sesión</a>.</p>
                    </div>
                </div>
            <?php endif; ?>
            <! -- Mostrar errores en caso de haberlos en el registro -->
            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Errores en el formulario:</strong>
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <! -- Formulario de registro -->
            <?php if (!$exito): ?>
                <form method="POST" action="" class="auth-form">
                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-user"></i>
                            Nombre completo
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            placeholder="Ingresa tu nombre completo"
                            value="<?php echo htmlspecialchars($nombre ?? ''); ?>"
                            required
                        >
                    </div>

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
                            value="<?php echo htmlspecialchars($correo ?? ''); ?>"
                            required
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
                            placeholder="Mínimo 6 caracteres"
                            required
                        >
                        <small>La contraseña debe tener al menos 6 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_contrasena">
                            <i class="fas fa-lock"></i>
                            Confirmar contraseña
                        </label>
                        <input 
                            type="password" 
                            id="confirmar_contrasena" 
                            name="confirmar_contrasena" 
                            placeholder="Confirma tu contraseña"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i>
                        Registrarme
                    </button>
                </form>
            <?php endif; ?>

            <div class="auth-footer">
                <p>¿Ya tienes cuenta? <a href="../index.php">Inicia sesión aquí</a></p>
                <p><a href="../index.php"><i class="fas fa-home"></i> Volver al inicio</a></p>
            </div>
        </div>
    </div>
    <! -- Scripts -->
    <script src="../js/config.js"></script>
    <script src="../js/notifications.js"></script>
    <script src="../js/auth.js"></script>
</body>
</html>
