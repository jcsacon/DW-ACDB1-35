/**
 * Sistema centralizado de notificaciones
 */

const Notifications = {
    /**
     * Mostrar una notificación
     * @param {string} mensaje - Mensaje a mostrar
     * @param {string} tipo - Tipo de notificación (success, error, info)
     */
    mostrar(mensaje, tipo = 'info') {
        const notificacion = document.createElement('div');
        notificacion.className = `notificacion notificacion-${tipo}`;
        notificacion.textContent = mensaje;
        
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 400px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        `;
        
        const colores = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
        };
        
        notificacion.style.background = colores[tipo] || colores.info;
        
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
    },

    /**
     * Mostrar notificación de éxito
     * @param {string} mensaje 
     */
    exito(mensaje) {
        this.mostrar(mensaje, 'success');
    },

    /**
     * Mostrar notificación de error
     * @param {string} mensaje 
     */
    error(mensaje) {
        this.mostrar(mensaje, 'error');
    },

    /**
     * Mostrar notificación informativa
     * @param {string} mensaje 
     */
    info(mensaje) {
        this.mostrar(mensaje, 'info');
    }
};

console.log('📢 Sistema de notificaciones cargado correctamente');