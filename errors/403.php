<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403 - Acceso Prohibido</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos del proyecto -->
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/auth.css">
    <style>
        /* Estilos específicos para páginas de error */
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-hero);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .error-card {
            background: var(--bg-card);
            border-radius: 25px;
            box-shadow: var(--shadow-xl);
            max-width: 550px;
            width: 100%;
            padding: 3rem;
            text-align: center;
            position: relative;
            z-index: 2;
            animation: slideIn 0.5s ease;
        }

        .error-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 50%;
            box-shadow: var(--shadow-md);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-icon i {
            font-size: 3rem;
            color: white;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .error-message {
            color: var(--text-secondary);
            font-size: 1.05rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .warning-box {
            background: rgba(245, 158, 11, 0.1);
            border-left: 4px solid #f59e0b;
            border-radius: 0 12px 12px 0;
            padding: 1.25rem;
            text-align: left;
            margin-bottom: 2rem;
        }

        .warning-box h3 {
            color: #d97706;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .warning-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .warning-box li {
            color: var(--text-secondary);
            font-size: 0.95rem;
            padding: 0.35rem 0;
            padding-left: 1.5rem;
            position: relative;
        }

        .warning-box li::before {
            content: '•';
            position: absolute;
            left: 0.5rem;
            color: #f59e0b;
        }

        .error-details {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: var(--bg-secondary);
            border-radius: 12px;
            text-align: left;
            font-size: 0.9rem;
        }

        .error-details p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .error-details code {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85rem;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .error-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class="fas fa-lock"></i>
            </div>
            
            <div class="error-code">403</div>
            
            <h1 class="error-title">Acceso Prohibido</h1>
            
            <p class="error-message">
                No tiene permisos para acceder a este recurso. 
                Si cree que esto es un error, contacte al administrador.
            </p>

            <div class="warning-box">
                <h3><i class="fas fa-exclamation-circle"></i> Posibles causas:</h3>
                <ul>
                    <li>No ha iniciado sesión en el sistema</li>
                    <li>Su sesión ha expirado</li>
                    <li>No tiene los permisos necesarios para esta sección</li>
                    <li>El recurso está protegido por configuración del servidor</li>
                </ul>
            </div>

            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
                <a href="/login" class="btn btn-secondary" style="color: var(--text-primary); border-color: var(--border-color);">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            </div>

            <div class="error-details">
                <p><strong>URL solicitada:</strong> <code><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></code></p>
                <p><strong>Fecha y hora:</strong> <code><?php echo date('d/m/Y H:i:s'); ?></code></p>
            </div>
        </div>
    </div>

    <!-- Script para tema oscuro -->
    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</body>
</html>
