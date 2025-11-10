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

        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            // La animación 'slideInRight' se encarga de mostrarlo
        }, 100);
        
        setTimeout(() => {
            notificacion.classList.add('fade-out');
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