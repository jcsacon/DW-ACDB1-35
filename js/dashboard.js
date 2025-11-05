/**
 * JavaScript para Dashboard y Páginas Protegidas
 */

document.addEventListener('DOMContentLoaded', () => {
    // Animaciones de entrada para tarjetas
    animarTarjetas();
    
    // Inicializar tooltips
    inicializarTooltips();
    
    // Verificar tiempo de sesión
    verificarTiempoSesion();
    
    // Agregar efectos hover mejorados
    mejorarEfectosHover();
    
    // Confirmar cierre de sesión
    confirmarCierreSesion();
    
    console.log('📊 Dashboard cargado correctamente');
});

/**
 * Animar entrada de tarjetas
 */
function animarTarjetas() {
    const tarjetas = document.querySelectorAll('.info-card, .action-card, .profile-card');
    
    tarjetas.forEach((tarjeta, index) => {
        tarjeta.style.opacity = '0';
        tarjeta.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            tarjeta.style.transition = 'all 0.5s ease';
            tarjeta.style.opacity = '1';
            tarjeta.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

/**
 * Inicializar tooltips personalizados
 */
function inicializarTooltips() {
    const elementosConTooltip = document.querySelectorAll('[title]');
    
    elementosConTooltip.forEach(elemento => {
        const titulo = elemento.getAttribute('title');
        if (!titulo) return;
        
        elemento.removeAttribute('title');
        
        elemento.addEventListener('mouseenter', (e) => {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-personalizado';
            tooltip.textContent = titulo;
            tooltip.style.cssText = `
                position: fixed;
                background: rgba(0, 0, 0, 0.9);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-size: 0.875rem;
                z-index: 10000;
                pointer-events: none;
                white-space: nowrap;
                animation: fadeIn 0.2s ease;
            `;
            
            document.body.appendChild(tooltip);
            
            const rect = elemento.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.bottom + 10 + 'px';
            
            elemento._tooltip = tooltip;
        });
        
        elemento.addEventListener('mouseleave', () => {
            if (elemento._tooltip) {
                elemento._tooltip.remove();
                elemento._tooltip = null;
            }
        });
    });
}

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
        <div style="
            position: fixed;
            top: 80px;
            right: 20px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            max-width: 350px;
            animation: slideInRight 0.5s ease;
        ">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
                <div>
                    <strong style="display: block; margin-bottom: 0.25rem;">Sesión próxima a expirar</strong>
                    <p style="margin: 0; font-size: 0.9rem; opacity: 0.95;">
                        Tu sesión expirará en 1 minuto. Realiza alguna acción para mantenerla activa.
                    </p>
                </div>
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
 * Mejorar efectos hover en tarjetas
 */
function mejorarEfectosHover() {
    const tarjetas = document.querySelectorAll('.info-card, .action-card');
    
    tarjetas.forEach(tarjeta => {
        tarjeta.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        tarjeta.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
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

/**
 * Contador animado para números
 */
function animarContador(elemento, valorFinal, duracion = 2000) {
    const valorInicial = parseInt(elemento.textContent) || 0;
    const incremento = (valorFinal - valorInicial) / (duracion / 16);
    let valorActual = valorInicial;
    
    const timer = setInterval(() => {
        valorActual += incremento;
        if (valorActual >= valorFinal) {
            elemento.textContent = valorFinal;
            clearInterval(timer);
        } else {
            elemento.textContent = Math.floor(valorActual);
        }
    }, 16);
}

/**
 * Validar formularios de perfil
 */
document.querySelectorAll('.profile-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const inputs = this.querySelectorAll('input[required]:not([disabled])');
        let valido = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                valido = false;
                input.style.borderColor = '#ef4444';
                
                setTimeout(() => {
                    input.style.borderColor = '';
                }, 2000);
            }
        });
        
        if (!valido) {
            e.preventDefault();
            mostrarNotificacion('Por favor, completa todos los campos requeridos', 'error');
        }
    });
});

/**
 * Mostrar notificación
 */
function mostrarNotificacion(mensaje, tipo = 'info') {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion notificacion-${tipo}`;
    
    const iconos = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    };
    
    const colores = {
        success: 'linear-gradient(135deg, #10b981, #059669)',
        error: 'linear-gradient(135deg, #ef4444, #dc2626)',
        info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
    };
    
    notificacion.innerHTML = `
        <i class="fas ${iconos[tipo]}"></i>
        <span>${mensaje}</span>
    `;
    
    notificacion.style.cssText = `
        position: fixed;
        top: 90px;
        right: 20px;
        background: ${colores[tipo]};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        z-index: 10000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        max-width: 400px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
    `;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.style.opacity = '1';
        notificacion.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notificacion.style.opacity = '0';
        notificacion.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notificacion.parentElement) {
                notificacion.remove();
            }
        }, 300);
    }, 5000);
}

/**
 * Animación de carga para tarjetas de estadísticas
 */
document.querySelectorAll('.card-value').forEach(elemento => {
    const valor = parseInt(elemento.textContent);
    if (!isNaN(valor) && valor > 0) {
        animarContador(elemento, valor);
    }
});

// Agregar estilo para animación slideInRight
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
document.head.appendChild(style);
