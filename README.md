# 📖 BookHeaven - Plataforma Premium de Lectura Digital

**BookHeaven** es una solución integral y moderna para la gestión y consumo de contenido literario digital (Libros, Mangas, Cómics y Audiolibros). Construida con un stack profesional, ofrece una experiencia de usuario fluida, un panel administrativo potente con analíticas avanzadas y despliegue optimizado para producción.

**Desarrollador:** RPSilent  
**Repositorio:** [github.com/RPSilent/BookHeaven](https://github.com/RPSilent/BookHeaven)  
**Última Actualización:** 13 de Marzo, 2026

---

## 🚀 Tecnologías Principales

### Backend

- **Framework:** Laravel 12.x (PHP 8.2+)
- **Autenticación:** Laravel Sanctum (Tokens JWT)
- **Base de Datos:** MySQL / MariaDB con optimización de índices
- **Gestión de Roles:** Sistema personalizado (Admin, Premium, Standard)
- **Reportes:** PDFs con Barryvdh/Laravel-DomPDF
- **Caching:** Redis/Cache para optimización

### Frontend

- **Framework:** React 19.x (Hooks & Context API)
- **Build Tool:** Vite 7.x
- **HTTP Client:** Axios con interceptores
- **Gráficos:** Recharts para analytics
- **Estilos:** CSS Vanilla (Premium Design)
- **Routing:** React Router v7

---

## ✨ Características Principales

### Para Usuarios

- **Catálogo Multiformato:** Libros, Mangas, Cómics y Audiolibros integrados
- **Perfil Premium:** Suscripción y pagos incluidos
- **Mi Lista (Favoritos):** Organización de lecturas futuras
- **Reseñas Sociales:** Valoraciones y comentarios comunitarios
- **Lector Integrado:**
  - Visor PDF con mejoras
  - Reproductor de audio
- **Perfil Personalizado:** Foto, biografía y preferencias
- **Registro Completo:** Captura de datos demográficos

### Para Administradores

- **Dashboard Analytics:**
  - Análisis demográfico avanzado (género, edad, país)
  - Métricas de contenido y usuarios
  - Actividad en tiempo real
- **Gestión de Contenido:** CRUD de todas las categorías
- **Gestión de Usuarios:** Asignación de roles y permisos
- **Auditoría:** Logs completos de actividades
- **Reportes:** Exportación PDF y estadísticas

---

## 📋 Estructura del Proyecto

```
BookHeaven/
├── backend/                        # API Laravel 12
│   ├── app/Models/                 # Modelos (User, Libro, Manga, Comic, Audiobook)
│   ├── app/Http/Controllers/API/   # Controladores
│   ├── app/Services/               # Lógica de negocio
│   ├── database/migrations/        # Migraciones BD
│   ├── routes/api.php              # Rutas API
│   ├── config/                     # Configuración
│   ├── composer.json               # Dependencias PHP
│   └── .env.example                # Variables de entorno
│
├── frontend/                       # Cliente React
│   ├── src/components/             # Componentes reutilizables
│   ├── src/pages/                  # Páginas/vistas
│   ├── src/context/                # Context API (Auth, etc)
│   ├── src/api/                    # Gestión HTTP
│   ├── src/styles/                 # CSS
│   ├── package.json                # Dependencias Node
│   ├── vite.config.js              # Config Vite
│   └── .env.example                # Variables de entorno
│
└── .github/                        # Workflows y configuración GitHub
```

---

## 🛠️ Instalación Local

### Requisitos

- Node.js 18+
- PHP 8.2+
- MySQL 5.7+
- Composer
- Git

### Paso 1: Clonar Repositorio

```bash
git clone https://github.com/RPSilent/BookHeaven.git
cd BookHeaven
```

### Paso 2: Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Crear BD: CREATE DATABASE bookheaven_db CHARACTER SET utf8mb4;
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Accesible en: `http://localhost:8000/api`

### Paso 3: Frontend (React)

```bash
cd ../frontend
npm install
cp .env.example .env
npm run dev
```

Accesible en: `http://localhost:5173`

### Credenciales de Prueba

```
Admin: admin@example.com / password
Usuario: usuario@example.com / password
```

---

## 🔑 Variables de Entorno

### Backend (.env)

```env
APP_NAME=BookHeaven
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
APP_KEY=base64:...

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=bookheaven_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
```

### Frontend (.env)

```env
VITE_API_URL=http://localhost:8000/api  # Desarrollo
# VITE_API_URL=https://tudominio.com/api  # Producción
```

---

## 📡 Endpoints API Principales

### Autenticación

- `POST /api/auth/register` - Registro
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/auth/me` - Usuario actual

### Contenido

- `GET /api/libros` - Listar libros
- `GET /api/mangas` - Listar mangas
- `GET /api/comics` - Listar cómics
- `GET /api/audiobooks` - Listar audiolibros
- `POST /api/content` - Crear (Admin)
- `PUT /api/content/{id}` - Editar (Admin)

### Favoritos & Reseñas

- `GET /api/favorites` - Mi lista
- `POST /api/favorites` - Agregar favorito
- `GET /api/reviews/{contentId}` - Reseñas
- `POST /api/reviews` - Crear reseña

### Admin

- `GET /api/admin/analytics` - Analytics
- `GET /api/admin/users` - Usuarios
- `GET /api/admin/activities` - Logs
- `POST /api/admin/reports/pdf` - Generar PDF

---

## ⚙️ Despliegue en Producción

### Hosting Compartido (Hostinger, DreamHost, etc)

**Pasos:**

1. **Preparar archivos:**

```bash
cd backend
composer install --no-dev
php artisan config:cache
php artisan route:cache

cd ../frontend
npm run build
```

2. **Subir vía SFTP:**
   - `/backend` → `public_html/api/`
   - `/frontend/dist` → `public_html/`
   - Crear symlink: `public_html/storage` → `public_html/api/storage/app/public`

3. **Configurar servidor:**

```bash
cd public_html/api
cp .env.example .env  # Editar con credenciales reales
php artisan migrate --force
php artisan storage:link
```

4. **Apache Configuration (.htaccess):**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

---

## 📊 Base de Datos

### Tablas Principales

| Tabla                                      | Propósito              |
| ------------------------------------------ | ---------------------- |
| `users`                                    | Usuarios del sistema   |
| `libros`, `mangas`, `comics`, `audiobooks` | Contenido              |
| `favorites`                                | Favoritos por usuario  |
| `reviews`                                  | Reseñas y valoraciones |
| `activity_logs`                            | Auditoría              |
| `roles`, `permissions`                     | Control de acceso      |

---

## 🧪 Testing

```bash
# Backend (PHPUnit)
cd backend
php artisan test

# Frontend
cd frontend
npm run test
```

---

## 🐛 Solución de Problemas

- **Conexión BD:** Verificar credenciales en `.env`
- **CORS:** Configurado en `config/cors.php`
- **Storage:** `php artisan storage:link`
- **Caché:** `php artisan config:clear && php artisan route:clear`

---

## 📈 Roadmap

- [ ] PWA (Lectura offline)
- [ ] Push Notifications
- [ ] Gamificación (Logros, badges)
- [ ] Recomendaciones IA
- [ ] Múltiples idiomas
- [ ] Integración pagos (Stripe, PayPal)

---

## 📝 Cambios Recientes (v1.0)

**13 de Marzo, 2026**

- ✅ Migración GitHub a RPSilent/BookHeaven
- ✅ README completamente actualizado
- ✅ Limpieza de archivos innecesarios
- ✅ Documentación detallada de endpoints
- ✅ Guía de despliegue en producción

---

## 📄 Licencia

MIT License - Ver archivo `LICENSE`

---

**Hecho con ❤️ por RPSilent - BookHeaven © 2026**
