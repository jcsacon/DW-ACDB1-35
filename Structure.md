```
└── 📁acceso                # Sistema principal de control de acceso
    └── 📁auth              # Módulo de autenticación y gestión de usuarios
        ├── logout.php      # Maneja el cierre de sesión y limpieza de datos
        ├── register.php    # Gestiona el registro de nuevos usuarios y validaciones
    └── 📁config            # Configuraciones globales del sistema
        ├── database.php    # Credenciales y configuración de la base de datos
        ├── sesion.php      # Configuración y manejo de sesiones de usuario
    └── 📁css               # Hojas de estilo del sistema
        ├── auth.css        # Estilos específicos para formularios de autenticación
        ├── dashboard.css   # Estilos para el panel de control principal
        ├── styles.css      # Estilos globales y componentes compartidos
    └── 📁js                # Scripts de JavaScript para funcionalidad del cliente
        ├── auth.js         # Validaciones y lógica de autenticación frontend
        ├── config.js       # Variables y configuraciones globales de JavaScript
        ├── dashboard.js    # Funcionalidades interactivas del dashboard
        ├── notifications.js # Sistema de notificaciones y alertas
        ├── theme.js        # Control del tema claro/oscuro y preferencias
    └── 📁protected         # Área protegida (requiere autenticación)
        ├── admin.php       # Panel de administración para usuarios privilegiados
        ├── dashboard.php   # Panel principal con resumen y funciones básicas
        ├── profile.php     # Gestión del perfil y preferencias del usuario
    ├── database.sql        # Script SQL con estructura y datos iniciales
    ├── index.php          # Punto de entrada principal y página de login
    └── README.md          # Documentación técnica y guía de instalación
```