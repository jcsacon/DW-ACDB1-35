/**
 * JavaScript para Dashboard y Páginas Protegidas
 */

// Gestión de sesión y notificaciones
document.addEventListener('DOMContentLoaded', () => {
    // Verificar tiempo de sesión
    verificarTiempoSesion();
    
    // Confirmar cierre de sesión
    confirmarCierreSesion();
    
    console.log('📊 Dashboard cargado correctamente');
});

/**
 * Verificar tiempo de sesión y mostrar advertencia
 */
function verificarTiempoSesion() {
    const TIEMPO_SESION = CONFIG.SESSION.DURATION;
    const TIEMPO_ADVERTENCIA = TIEMPO_SESION - CONFIG.SESSION.WARNING_TIME;
    
    let tiempoInicio = Date.now();
    
    // Verificar según el intervalo configurado
    setInterval(() => {
        const tiempoTranscurrido = Date.now() - tiempoInicio;
        // Mostrar advertencia o cerrar sesión
        if (tiempoTranscurrido >= TIEMPO_ADVERTENCIA && tiempoTranscurrido < TIEMPO_SESION) {
            mostrarAdvertenciaSesion();
        } else if (tiempoTranscurrido >= TIEMPO_SESION) {
            window.location.href = '../auth/logout.php';
        }
    }, CONFIG.SESSION.CHECK_INTERVAL);
    
    // Resetear tiempo al detectar actividad
    ['click', 'keypress', 'scroll', 'mousemove'].forEach(evento => {
        let timeout;
        document.addEventListener(evento, () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                tiempoInicio = Date.now();
            }, 1000);
        });
    });
}

/**
 * Mostrar advertencia de sesión próxima a expirar
 */
function mostrarAdvertenciaSesion() {
    const advertenciaExiste = document.querySelector('.advertencia-sesion');
    if (advertenciaExiste) return;
    
    const advertencia = document.createElement('div');
    advertencia.className = 'advertencia-sesion';
    
    advertencia.innerHTML = `
        <div class="advertencia-sesion-content">
            <i class="fas fa-clock"></i>
            <div class="advertencia-sesion-text">
                <strong>Sesión próxima a expirar</strong>
                <p>
                    Hola, Tu sesión expirará en 1 minuto. Realiza alguna acción para mantenerla activa.
                </p>
            </div>
        </div>
    `;
    
    document.body.appendChild(advertencia);
    
    // Remover después de 10 segundos
    setTimeout(() => {
        if (advertencia.parentElement) {
            advertencia.style.opacity = '0';
            setTimeout(() => advertencia.remove(), 300);
        }
    }, 10000);
}

/**
 * Confirmar cierre de sesión
 */
function confirmarCierreSesion() {
    const btnLogout = document.querySelector('.btn-logout');
    
    if (btnLogout) {
        btnLogout.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                e.preventDefault();
            }
        });
    }
}
