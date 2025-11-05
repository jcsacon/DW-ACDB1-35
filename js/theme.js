/**
 * Gestor del tema de la aplicación
 */
class ThemeManager {
    constructor() {
        this.themeToggle = document.getElementById('theme-toggle');
        this.themeIcon = document.getElementById('theme-icon');
        this.currentTheme = localStorage.getItem('theme') || 'light';
        
        this.init();
    }
    
    init() {
        // Aplicar el tema guardado
        this.applyTheme(this.currentTheme);
        
        // Agregar event listener al botón
        if (this.themeToggle) {
            this.themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }
    
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        this.currentTheme = theme;
        
        // Actualizar el icono
        if (this.themeIcon) {
            this.themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        
        // Cambiar el logo según el tema
        const logo = document.querySelector('.logo');
        if (logo && logo.getAttribute('data-theme-src')) {
            logo.src = theme === 'dark' ? 
                logo.getAttribute('data-theme-src') : 
                logo.getAttribute('data-theme-src').replace('-dark', '');
        }
        
        // Guardar en localStorage
        localStorage.setItem('theme', theme);
        
        // Actualizar el navbar según el scroll y el tema
        this.updateNavbarStyle();
    }
    
    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        
        // Agregar efecto visual al botón
        if (this.themeToggle) {
            this.themeToggle.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.themeToggle.style.transform = 'scale(1)';
            }, 150);
        }
    }
    
    updateNavbarStyle() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;
        
        if (window.scrollY > 100) {
            navbar.style.background = this.currentTheme === 'dark' ? 
                'rgba(31, 41, 55, 0.98)' : 
                'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.background = this.currentTheme === 'dark' ? 
                'rgba(31, 41, 55, 0.95)' : 
                'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
        }
    }
}

// Inicializar el gestor de temas cuando se carga el documento
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Actualizar el navbar al hacer scroll
window.addEventListener('scroll', () => {
    if (window.themeManager) {
        window.themeManager.updateNavbarStyle();
    }
});

console.log('🎨 Sistema de temas cargado correctamente');