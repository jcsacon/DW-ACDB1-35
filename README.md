# DW-ACDB1-35
DESARROLLO WEB - 5to SEMESTRE - Implementación de Sistema de Login y Gestión de Sesiones

## Objetivo

Que los estudiantes comprendan y apliquen los conceptos de autenticación de usuarios y manejo de sesiones en PHP, desarrollando una solución práctica que garantice la seguridad y la correcta gestión del estado de usuario en una aplicación web.

## Descripción

Desarrollar un proyecto donde se implemente un sistema de login con PHP y MySQL que incluya:

- Registro de usuarios con validación básica.
- Inicio y cierre de sesión usando sesiones PHP para mantener la autenticación.
- Control de acceso a páginas restringidas solo para usuarios autenticados.

## Lineamientos

### Desarrollo Técnico:

- Crear base de datos con tabla usuarios que incluya al menos: id, nombre, correo, contraseña (hash).
- Formulario de registro y validación simple.
- Formulario de login que valide credenciales.
- Uso de sesiones PHP para mantener el estado del usuario.
- Página protegida que solo sea accesible con sesión activa.
- Opción para cerrar sesión que destruya la sesión activa.

## Estructura del Sistema
La estructura del sistema es la siguiente:
```
/
├── auth/                   # Archivos de autenticación
│   ├── logout.php         # Cierre de sesión
│   └── register.php       # Registro de usuarios
├── config/                # Configuraciones del sistema
│   ├── database.php       # Configuración de base de datos
│   └── sesion.php        # Gestión de sesiones
├── css/                   # Estilos CSS
│   ├── auth.css          # Estilos para autenticación
│   ├── dashboard.css     # Estilos para panel de control
│   └── styles.css        # Estilos globales
├── js/                    # Scripts JavaScript
│   ├── auth.js           # Funciones de autenticación
│   ├── config.js         # Configuraciones JS
│   ├── dashboard.js      # Funciones del panel
│   ├── notifications.js  # Sistema de notificaciones
│   └── theme.js          # Gestión de temas
├── protected/            # Área protegida
│   ├── admin.php        # Panel de administración
│   ├── dashboard.php    # Panel de usuario
│   └── profile.php      # Perfil de usuario
├── captcha.php          # Generador de CAPTCHA
├── database.sql         # Estructura de base de datos
├── index.php            # Página principal
└── README.md            # Documentación
```

## Instalación

1. **Requisitos Previos:**
   - XAMPP (versión 8.0 o superior)
   - PHP 8.0 o superior
   - MySQL 5.7 o superior

2. **Configuración de Base de Datos:**
   - Iniciar servicios de Apache y MySQL en XAMPP
   - Acceder a phpMyAdmin (http://localhost/phpmyadmin)
   - Crear una nueva base de datos llamada 'portafolio_db'
   - Importar el archivo `database.sql`

3. **Instalación del Sistema:**
   - Clonar o descargar el repositorio
   - Colocar los archivos en la carpeta: `C:/xampp/htdocs/acceso/`
   - Verificar permisos de escritura en las carpetas necesarias

4. **Configuración:**
   - Revisar y ajustar los parámetros en `/config/database.php`
   - Configurar las credenciales de base de datos si es necesario

5. **Acceso al Sistema:**
   - Abrir el navegador y acceder a: `http://localhost/acceso`
   - Credenciales por defecto:
     - Email: jhon@correo.com
     - Contraseña: admin123
     - Este usuario se encuentra en modo Administrador

## Consideraciones

1. **Seguridad:**
   - Las contraseñas se reforzo utilizando hash bcrypt

2. **Desarrollo:**
   - Documentación inline en el código
   - Sigue las mejores prácticas de PHP
   - Compatible con PHP 8.x

4. **Recomendaciones:**
   - Cambiar las credenciales por defecto
   - Configurar los límites de sesión según necesidades
