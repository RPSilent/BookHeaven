# Configuración de Vercel para BookHeaven Frontend

## Pasos para configurar el deployment en Vercel

### 1. Conectar GitHub con Vercel
- Ve a [vercel.com](https://vercel.com)
- Crea una cuenta o inicia sesión
- Haz clic en "New Project"
- Autoriza Vercel para acceder a tu GitHub
- Selecciona el repositorio `BookHeaven`

### 2. Configurar el proyecto
Cuando Vercel detecte el proyecto:

**Framework Preset:** Vite
**Root Directory:** `./frontend`
**Build Command:** `npm run build`
**Output Directory:** `dist`
**Install Command:** `npm install`

### 3. Configurar Variables de Entorno

En los Settings del proyecto en Vercel, ve a **Environment Variables** y agrega:

```
VITE_API_URL = <URL_DE_TU_BACKEND>
BACKEND_URL = <URL_DE_TU_BACKEND>
```

**Ejemplo:**
```
VITE_API_URL = https://tu-backend.com/api
BACKEND_URL = https://tu-backend.com
```

### 4. Deploy automático
Una vez configurado, cada push a la rama `main` desplegará automáticamente el frontend en Vercel.

### 5. Dominios personalizados (Opcional)
En los Settings de Vercel, ve a **Domains** y agrega tu dominio personalizado.

## Estructura del proyecto
- `/frontend` - Aplicación React con Vite
- `/backend` - API Laravel
- `.gitignore` - Archivos ignorados por Git
- `vercel.json` - Configuración de Vercel
- `/.vercelignore` - Archivos ignorados por Vercel

## Variables de Entorno Requeridas

El frontend necesita estas variables de entorno para funcionar correctamente:

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `VITE_API_URL` | URL de la API del backend | `https://api.bookheaven.com` |
| `BACKEND_URL` | URL base del backend | `https://api.bookheaven.com` |

## Configuración del vercel.json

El archivo `frontend/vercel.json` ya contiene:
- Configuración de build y output
- Headers de seguridad
- Cache control para assets estáticos
- Rewrites para API (locales por defecto)

### Actualizar Rewrites para Producción

Si tu backend está en un dominio diferente, actualiza las rewrites en `frontend/vercel.json`:

```json
"rewrites": [
  {
    "source": "/api/:path*",
    "destination": "https://tu-api-domain.com/api/:path*"
  },
  {
    "source": "/storage/:path*",
    "destination": "https://tu-api-domain.com/storage/:path*"
  }
]
```

## Monitoreo y Logs

En el dashboard de Vercel puedes:
- Ver los logs de build
- Revisar los analytics
- Monitorear el rendimiento
- Rollback a deployments anteriores
