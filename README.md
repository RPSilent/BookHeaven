# 📖 BookHeaven - Plataforma de Lectura Digital

**BookHeaven** es una solución integral y premium para la gestión y consumo de contenido literario digital (Libros, Mangas, Cómics y Audiolibros). Construida con un stack moderno, ofrece una experiencia de usuario fluida y un panel administrativo potente con analíticas avanzadas.

---

## 🚀 Tecnologías Principales

### Backend

- **Framework:** Laravel 12.x (PHP 8.2+)
- **Autenticación:** Laravel Sanctum (Basada en Tokens)
- **Base de Datos:** MySQL / MariaDB con optimización de índices.
- **Gestión de Roles:** Sistema personalizado de roles (Admin, Premium, Standard).
- **Reportes:** Generación de PDFs integrada.

### Frontend

- **Framework:** React 19.x
- **Build Tool:** Vite 7.x
- **Estado Global:** Context API
- **Gráficos/Analíticas:** Recharts
- **Estilos:** CSS Vanilla (Diseños premium y dinámicos)
- **Iconos:** Emojis y Micro-interacciones personalizadas.

---

## ✨ Características Principales

### Para Usuarios

- **Catálogo Multiformato:** Exploración de libros, mangas, cómics y audiolibros.
- **Perfil Premium:** Flujo de suscripción y pagos integrado.
- **Mi Lista:** Sistema de favoritos para organizar lecturas futuras.
- **Reseñas:** Interacción social mediante valoraciones y comentarios.
- **Lector Integrado:** Visor de PDFs y reproductor de audio nativo.
- **Perfil Personalizado:** Foto de perfil, biografía y preferencias de lectura.
- **Registro Detallado:** Captura de datos demográficos (país, género, fecha de nacimiento) para una experiencia personalizada.

### Para Administradores (Dashboard Analytics)

- **Analíticas Demográficas:** Comparativas de lectura por:
  - Género (Hombres vs Mujeres vs Otros).
  - Rango de edad (13-18, 19-25, etc.).
  - Ubicación geográfica (Top países).
  - Tipo de usuario (Suscritos vs Estándar).
- **Gestión de Usuarios:** CRUD completo de usuarios y asignación de roles.
- **Estadísticas de Contenido:** Rendimiento de títulos populares y contenido premium.
- **Logs de Actividad:** Historial detallado de acciones en la plataforma.
- **Reportes:** Exportación de datos críticos.

---

## 🛠️ Instalación y Configuración

### 1. Clonar el repositorio

```bash
git clone https://github.com/RPSilent/BookHeaven.git
```

### 2. Configurar el Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### 3. Configurar el Frontend (React)

```bash
cd frontend
npm install
npm run dev
```

---

## 📁 Estructura del Proyecto

```
BookHeaven/
├── backend/                 # API REST con Laravel 12
│   ├── app/                # Código principal
│   │   ├── Http/           # Controllers, Requests, Middleware
│   │   ├── Models/         # Modelos Eloquent
│   │   ├── Services/       # Lógica de negocio
│   │   └── Jobs/           # Colas de trabajo
│   ├── config/             # Configuración de aplicación
│   ├── database/           # Migrations y Seeds
│   ├── routes/             # Endpoints API
│   ├── public/             # Punto de entrada público
│   ├── hostinger-*.php     # Herramientas para Hostinger
│   └── composer.json       # Dependencias PHP
│
├── frontend/               # Aplicación React + Vite
│   ├── src/
│   │   ├── api/           # Cliente HTTP (Axios)
│   │   ├── components/    # Componentes React reutilizables
│   │   ├── pages/         # Vistas completas
│   │   ├── context/       # Estado global (AuthContext, etc)
│   │   ├── styles/        # CSS y temas
│   │   └── utils/         # Funciones de utilidad
│   ├── public/            # Archivos estáticos
│   └── package.json       # Dependencias JavaScript
│
└── README.md             # Este archivo
```

---

## 🛠️ Scripts de Utilidad (Backend)

El proyecto incluye herramientas para mantenimiento, diagnóstico y configuración:

### Herramientas de Hostinger

- **`hostinger-setup.php`** - Panel web para ejecutar comandos Laravel sin acceso SSH
- **`hostinger-migrate-safe.php`** - Migraciones seguras (alternativa a exec() deshabilitada)
- **`hostinger-diagnose.php`** - Diagnóstico completo del servidor y configuración

### Herramientas Auxiliares

- **`create-storage-link-manual.php`** - Crear links de almacenamiento sin artisan
- **`fix_paths.php`** - Corregir rutas de archivos y configuraciones
- **`check-content.php`** - Validador de integridad de datos
- **`verify_users.sql`** - Script SQL para verificación de usuarios

---

## 🌐 Despliegue en Producción

### 📦 Despliegue en Hostinger (RECOMENDADO)

El proyecto está completamente optimizado para Hostinger con herramientas de configuración y migración integradas.

#### 🚀 Configuración Rápida

1. **Subir archivos al servidor:**
   ```
   public_html/ (Frontend React)
   public_html/api/ (Backend Laravel)
   ```

2. **Ejecutar configuración:**
   - Accede a: `https://tudominio.com/api/hostinger-setup.php?password=tu_password`
   - Ejecuta los comandos necesarios desde el panel web

3. **Realizar migraciones:**
   - Accede a: `https://tudominio.com/api/hostinger-migrate-safe.php?password=tu_password`
   - Las migraciones se ejecutarán sin usar `exec()` (deshabilitado en Hostinger)

4. **Verificar diagnóstico:**
   - Accede a: `https://tudominio.com/api/hostinger-diagnose.php?password=tu_password`
   - Comprueba que todo esté correctamente configurado

#### ⚠️ Consideraciones de Seguridad

- **Cambiar contraseñas en los scripts:** Edita `hostinger-setup.php`, `hostinger-migrate-safe.php` y `hostinger-diagnose.php` para cambiar la contraseña por defecto (`cambiar_este_password_123`)
- **Eliminar scripts después del despliegue:** Borra los archivos `.php` de configuración después de completar la instalación
- **Proteger .env:** Asegúrate de que `.env` NO sea accesible desde la web

---

## � Cambios Recientes y Actualizaciones

### v2.0.0 - Migración Completa a Hostinger (Marzo 2026)

✅ **Implementado:**
- Migración completa del proyecto desde Vercel a Hostinger
- Adición de herramientas de configuración web (hostinger-setup.php, hostinger-migrate-safe.php)
- Sistema de diagnóstico para identificar problemas de configuración
- Scripts de utilidad para mantenimiento sin acceso SSH
- Actualización del repositorio: Cuenta RPSilent/BookHeaven en GitHub
- Optimización de rutas y almacenamiento para Hostinger
- Validación de integridad de datos y usuarios

**Nota:** Se han removido dependencias de Vercel. El proyecto está completamente orientado a Hostinger.

---

## 📈 Próximas Mejoras (Roadmap)

- [ ] **Lector PDF Pro:** Integración con PDF.js para modo nocturno y marcadores.
- [ ] **Modo Offline (PWA):** Lectura sin conexión mediante Service Workers.
- [ ] **Gamificación:** Sistema de logros y rachas de lectura.
- [ ] **Internacionalización:** Soporte completo para Inglés (en proceso).
- [ ] **Notificaciones Push:** Alertas de nuevos lanzamientos y promociones.
- [ ] **API GraphQL:** Alternativa a REST API para queries más eficientes.

---
