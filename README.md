# 🏢 Urban CMS — Sistema de Gestión Inmobiliaria y Blog

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Intervention Image](https://img.shields.io/badge/Intervention_Image-3.x-FF6B6B?style=for-the-badge&logo=image&logoColor=white)](https://image.intervention.io)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)

**CMS completo para gestión de propiedades inmobiliarias y blog corporativo con API REST.**

[Ver sitio](https://urbanarquitectura.mx) · [Características](#-características) · [Arquitectura](#-arquitectura) · [Instalación](#-instalación)

</div>

---

## 💡 ¿Qué es Urban CMS?

**Urban CMS** es un sistema de gestión de contenido desarrollado para una empresa del sector inmobiliario. Permite administrar un catálogo de propiedades (venta y renta), mantener un blog corporativo con artículos de interés, y exponer toda la información mediante una **API REST** para integración con el sitio web público.

> 🎯 **Objetivo:** Centralizar la gestión de contenido inmobiliario y editorial en una sola plataforma, conectada al frontend público mediante API.

---

## ✨ Características

### 🏠 Gestión de Propiedades
- **CRUD completo** de propiedades inmobiliarias.
- Campos detallados: ubicación, características (hasta 8 features), Google Maps, tipo de operación (venta/renta), inversión.
- **Galería de imágenes** con soporte para múltiples fotos por propiedad.
- **Seeders de producción** con datos de ejemplo (modern loft, historic house, retail space, etc.).

### 📝 Blog / Artículos
- **Editor enriquecido** para contenido de artículos.
- **SEO optimizado:** Meta title, meta description, keywords, slug automático.
- **Galería de imágenes** por artículo con reordenamiento.
- **Ficha técnica** descargable (datasheet).
- **Fecha de publicación** programable.

### 💬 Sistema de Comentarios
- Comentarios en artículos del blog.
- **Moderación:** Aprobación/rechazo de comentarios.
- API pública para crear y listar comentarios.

### 🖼️ Optimización de Imágenes
- **Conversión automática a WebP** para mejor rendimiento.
- **Nombres SEO-friendly** para archivos de imagen.
- Reordenamiento de imágenes en galerías.
- Trait reutilizable `HandlesImageProcessing`.

### 🔌 API REST
- **Endpoints de propiedades:** Listado y detalle.
- **Endpoints de artículos:** Listado, destacados, detalle, relacionados.
- **Endpoints de comentarios:** Crear, listar, aprobar.
- **CORS habilitado** para consumo desde frontend externo.
- **Rate limiting** para protección.

### 🔐 Autenticación y Panel
- Sistema de autenticación con Laravel Breeze.
- Panel de control con acceso a propiedades y blog.
- Gestión de perfil de usuario.

---

## 🏗️ Arquitectura

```text
app/
├── Console/Commands/
│   └── ConvertImagesToWebP.php         # Comando para conversión masiva
├── Http/Controllers/
│   ├── Api/
│   │   ├── ArticleController.php       # API REST de artículos
│   │   ├── CommentController.php       # API REST de comentarios
│   │   └── PropertyController.php      # API REST de propiedades
│   ├── ArticleController.php           # CRUD de artículos (web)
│   ├── CommentController.php           # Moderación de comentarios
│   └── PropertyController.php          # CRUD de propiedades (web)
├── Http/Middleware/
│   ├── ApiRateLimitMiddleware.php      # Rate limiting
│   ├── ApiTokenMiddleware.php          # Autenticación API
│   └── CorsMiddleware.php              # CORS para frontend
├── Http/Resources/
│   ├── PropertyCollection.php
│   └── PropertyResource.php
├── Models/
│   ├── Article.php                     # Artículos con relaciones
│   ├── ArticleImage.php                # Imágenes de artículos
│   ├── Comment.php                     # Comentarios
│   ├── Property.php                    # Propiedades inmobiliarias
│   ├── PropertyImage.php               # Imágenes de propiedades
│   └── User.php
├── Traits/
│   └── HandlesImageProcessing.php      # Procesamiento de imágenes
└── ...

database/seeders/
├── AdminUserSeeder.php                 # Usuario admin por defecto
├── ArticleSeeder.php                   # Artículos de ejemplo
├── ProductionSeeder.php                # Datos para producción
└── PropertySeeder.php                  # Propiedades de ejemplo
```

### Tech Stack
- **Laravel 12.x** — Framework PHP
- **Laravel Breeze** — Autenticación y scaffolding
- **Intervention Image** — Procesamiento de imágenes
- **Tailwind CSS** — Estilos utilitarios
- **SQLite/MySQL** — Base de datos
- **API REST** — JSON con CORS y rate limiting

---

## 🚀 Instalación

### Requisitos
- PHP `^8.2`
- Composer `2.x`
- Node.js `18+`
- SQLite o MySQL

```bash
# 1. Clonar
git clone https://github.com/ebravo-dev/urbancms.git
cd urbancms

# 2. Dependencias
composer install
npm install

# 3. Configuración
cp .env.example .env
php artisan key:generate

# 4. Base de datos
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=ProductionSeeder

# 5. Assets
npm run build

# 6. Iniciar
php artisan serve
```

### Credenciales por defecto
| Usuario | Email | Password |
|---------|-------|----------|
| Admin | admin@example.com | password |

---

## 🔌 API Endpoints

### Propiedades
```http
GET /api/properties              # Listar propiedades
GET /api/properties/{id}         # Ver detalle
```

### Artículos (Blog)
```http
GET /api/articles                # Listar artículos
GET /api/articles/featured       # Destacados
GET /api/articles/{slug}         # Ver detalle
GET /api/articles/{slug}/related # Artículos relacionados
```

### Comentarios
```http
GET  /api/articles/{slug}/comments           # Listar comentarios aprobados
POST /api/articles/{slug}/comments           # Crear comentario
GET  /api/articles/{slug}/comments/{id}      # Ver comentario
```

---

## 📁 Documentación Adicional

- `API_DOCUMENTATION.md` — Documentación general de la API
- `BLOG_API_DOCUMENTATION.md` — Endpoints específicos del blog
- `FRONTEND_INTEGRATION_GUIDE.md` — Guía de integración con frontend
- `docs/SEO_IMAGE_NAMES.md` — Estrategia de nombres SEO para imágenes
- `docs/SEO_EXAMPLES.md` — Ejemplos prácticos de SEO

---

## 🌐 Sitio en Producción

El frontend público de este CMS está disponible en:

👉 **[https://urbanarquitectura.mx](https://urbanarquitectura.mx)**

---

## 📄 Licencia

Proyecto privado para cliente.  
Desarrollado por [Eder J. G. Bravo](https://github.com/ebravo-dev).

---

> *"Hecho para conectar propiedades con personas."* 🏡
