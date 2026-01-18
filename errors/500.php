<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500 - Error del Servidor</title>
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
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border-radius: 50%;
            box-shadow: var(--shadow-md);
        }

        .error-icon i {
            font-size: 3rem;
            color: white;
            animation: spin 3s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #dc2626, #991b1b);
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

        .btn-retry {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
        }

        .info-box {
            background: rgba(220, 38, 38, 0.1);
            border-left: 4px solid #dc2626;
            border-radius: 0 12px 12px 0;
            padding: 1.25rem;
            text-align: left;
            margin-bottom: 2rem;
        }

        .info-box h3 {
            color: #dc2626;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            padding-left: 0.5rem;
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
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85rem;
        }

        .retry-timer {
            margin-top: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .retry-timer span {
            color: var(--primary-color);
            font-weight: 600;
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
                <i class="fas fa-cog"></i>
            </div>
            
            <div class="error-code">500</div>
            
            <h1 class="error-title">Error Interno del Servidor</h1>
            
            <p class="error-message">
                Algo salió mal en el servidor
            </p>

            <div class="info-box">
                <h3><i class="fas fa-lightbulb"></i> ¿Qué puede hacer?</h3>
                <p>• Espere unos segundos e intente nuevamente</p>
                <p>• Verifique su conexión a internet</p>
                <p>• Si el problema persiste, contacte al administrador</p>
            </div>

            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
                <button onclick="location.reload()" class="btn btn-retry">
                    <i class="fas fa-sync-alt"></i> Reintentar
                </button>
            </div>

            <div class="error-details">
                <p><strong>URL solicitada:</strong> <code><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></code></p>
                <p><strong>Fecha y hora:</strong> <code><?php echo date('d/m/Y H:i:s'); ?></code></p>
                <p><strong>ID de error:</strong> <code><?php echo 'ERR-' . time() . '-' . rand(1000, 9999); ?></code></p>
            </div>

            <p class="retry-timer">
                La página se recargará automáticamente en <span id="countdown">10</span> segundos...
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Verificar preferencia de tema guardada
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }

        // Contador regresivo para recargar la página
        let seconds = 10;
        const countdownEl = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(timer);
                location.reload();
            }
        }, 1000);
    </script>
</body>
</html>
