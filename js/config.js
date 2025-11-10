/**
 * Configuración global del sistema
 */

const CONFIG = {
    // Tiempos de sesión
    SESSION: {
        DURATION: 300000,        // 5 minutos en milisegundos
        WARNING_TIME: 60000,     // 1 minuto en milisegundos antes de expirar
        CHECK_INTERVAL: 10000    // Verificar cada 10 segundos
    },

    // Valores de validación de formularios de autenticación
    VALIDATION: {
        MIN_NAME_LENGTH: 3,
        MIN_PASSWORD_LENGTH: 6,
        EMAIL_REGEX: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    },

    // Rutas del sistema para redirecciones de autenticación
    ROUTES: {
        LOGOUT: '../auth/logout.php',
        LOGIN: '../index.php',
        DASHBOARD: '../protected/dashboard.php'
    },

    // Configuración de notificaciones
    NOTIFICATIONS: {
        DURATION: 5000,          // Duración de las notificaciones en ms
        POSITION: 'top-right'    // Posición de las notificaciones
    }
};

Object.freeze(CONFIG); // Hacer inmutable la configuración global

console.log('⚙️ Configuración del sistema cargada correctamente');