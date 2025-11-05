/**
 * JavaScript para Páginas de Autenticación
 * Login y Registro
 */

document.addEventListener('DOMContentLoaded', () => {
    // Animación de entrada para la tarjeta de autenticación
    const authCard = document.querySelector('.auth-card');
    if (authCard) {
        authCard.style.opacity = '0';
        authCard.style.transform = 'translateY(-30px)';
        
        setTimeout(() => {
            authCard.style.transition = 'all 0.5s ease';
            authCard.style.opacity = '1';
            authCard.style.transform = 'translateY(0)';
        }, 100);
    }

    // Validación en tiempo real del formulario de registro
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        const nombreInput = document.getElementById('nombre');
        const correoInput = document.getElementById('correo');
        const contrasenaInput = document.getElementById('contrasena');
        const confirmarInput = document.getElementById('confirmar_contrasena');

        // Validar nombre
        if (nombreInput) {
            nombreInput.addEventListener('blur', () => {
                if (nombreInput.value.length < 3) {
                    mostrarError(nombreInput, 'El nombre debe tener al menos 3 caracteres');
                } else {
                    limpiarError(nombreInput);
                }
            });
        }

        // Validar correo
        if (correoInput) {
            correoInput.addEventListener('blur', () => {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(correoInput.value)) {
                    mostrarError(correoInput, 'Ingresa un correo válido');
                } else {
                    limpiarError(correoInput);
                }
            });
        }

        // Validar contraseña
        if (contrasenaInput) {
            contrasenaInput.addEventListener('input', () => {
                const strength = calcularFuerzaContrasena(contrasenaInput.value);
                mostrarFuerzaContrasena(contrasenaInput, strength);
            });
        }

        // Validar confirmación de contraseña
        if (confirmarInput && contrasenaInput) {
            confirmarInput.addEventListener('input', () => {
                if (confirmarInput.value !== contrasenaInput.value) {
                    mostrarError(confirmarInput, 'Las contraseñas no coinciden');
                } else {
                    limpiarError(confirmarInput);
                }
            });
        }
    }

    // Focus automático en el primer campo
    const firstInput = document.querySelector('.auth-form input:not([disabled])');
    if (firstInput) {
        firstInput.focus();
    }

    // Mostrar/ocultar contraseña
    agregarTogglePassword();

    // Animación de círculos decorativos
    animarCirculosDecorativos();
});

/**
 * Mostrar error en un campo
 */
function mostrarError(input, mensaje) {
    limpiarError(input);
    input.style.borderColor = '#ef4444';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'campo-error';
    errorDiv.textContent = mensaje;
    errorDiv.style.cssText = `
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        animation: fadeIn 0.3s ease;
    `;
    
    input.parentElement.appendChild(errorDiv);
}

/**
 * Limpiar error de un campo
 */
function limpiarError(input) {
    const errorDiv = input.parentElement.querySelector('.campo-error');
    if (errorDiv) {
        errorDiv.remove();
    }
    input.style.borderColor = '';
}

/**
 * Calcular fuerza de contraseña
 */
function calcularFuerzaContrasena(password) {
    let fuerza = 0;
    
    if (password.length >= 6) fuerza++;
    if (password.length >= 10) fuerza++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) fuerza++;
    if (/\d/.test(password)) fuerza++;
    if (/[^a-zA-Z0-9]/.test(password)) fuerza++;
    
    return fuerza;
}

/**
 * Mostrar indicador de fuerza de contraseña
 */
function mostrarFuerzaContrasena(input, fuerza) {
    let indicador = input.parentElement.querySelector('.fuerza-contrasena');
    
    if (!indicador) {
        indicador = document.createElement('div');
        indicador.className = 'fuerza-contrasena';
        indicador.style.cssText = `
            margin-top: 0.5rem;
            display: flex;
            gap: 0.25rem;
        `;
        input.parentElement.appendChild(indicador);
    }
    
    indicador.innerHTML = '';
    
    const colores = ['#ef4444', '#f59e0b', '#10b981'];
    const etiquetas = ['Débil', 'Media', 'Fuerte'];
    const nivel = Math.min(Math.floor(fuerza / 2), 2);
    
    for (let i = 0; i < 3; i++) {
        const barra = document.createElement('div');
        barra.style.cssText = `
            flex: 1;
            height: 4px;
            background: ${i <= nivel ? colores[nivel] : '#e5e7eb'};
            border-radius: 2px;
            transition: background 0.3s ease;
        `;
        indicador.appendChild(barra);
    }
    
    if (input.value.length > 0) {
        const texto = document.createElement('span');
        texto.textContent = etiquetas[nivel];
        texto.style.cssText = `
            font-size: 0.8rem;
            color: ${colores[nivel]};
            font-weight: 600;
            margin-left: 0.5rem;
        `;
        indicador.appendChild(texto);
    }
}

/**
 * Agregar funcionalidad para mostrar/ocultar contraseña
 */
function agregarTogglePassword() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        toggleBtn.style.cssText = `
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.5rem;
            transition: color 0.3s ease;
        `;
        
        toggleBtn.addEventListener('click', () => {
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
        
        toggleBtn.addEventListener('mouseenter', () => {
            toggleBtn.style.color = 'var(--primary-color)';
        });
        
        toggleBtn.addEventListener('mouseleave', () => {
            toggleBtn.style.color = 'var(--text-secondary)';
        });
        
        wrapper.appendChild(toggleBtn);
    });
}

console.log('🔐 Sistema de autenticación cargado correctamente');
