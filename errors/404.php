<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página No Encontrada</title>
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
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 50%;
            box-shadow: var(--shadow-md);
        }

        .error-icon i {
            font-size: 3rem;
            color: white;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ef4444, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
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

        .error-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .error-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-links a:hover {
            color: var(--primary-color);
        }

        .error-details {
            margin-top: 2rem;
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
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
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
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <div class="error-code">404</div>
            
            <h1 class="error-title">Página No Encontrada</h1>
            
            <p class="error-message">
                Lo sentimos, la página que estás buscando no existe o ha sido movida. 
                Verifica la URL o regresa al inicio.
            </p>

            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary" style="color: var(--text-primary); border-color: var(--border-color);">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>

            <div class="error-links">
                <a href="/login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
                <a href="/registro"><i class="fas fa-user-plus"></i> Registrarse</a>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </div>

            <div class="error-details">
                <p><strong>URL solicitada:</strong> <code><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></code></p>
                <p><strong>Fecha y hora:</strong> <code><?php echo date('d/m/Y H:i:s'); ?></code></p>
                <p><strong>IP del cliente:</strong> <code><?php echo $_SERVER['REMOTE_ADDR']; ?></code></p>
            </div>
        </div>
    </div>

    <!-- Script para tema oscuro -->
    <script>
        // Verificar preferencia de tema guardada
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</body>
</html>
