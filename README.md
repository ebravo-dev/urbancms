# Urban CMS - Sistema de Gestión de Contenido Inmobiliario

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## Acerca de Urban CMS

Urban CMS es un sistema de gestión de contenido especializado para el sector inmobiliario, desarrollado con Laravel. Esta plataforma permite a los usuarios, después de iniciar sesión, crear y gestionar publicaciones de propiedades inmobiliarias, incluyendo:

- Descripción detallada de las propiedades
- Información de precios
- Carga y gestión de imágenes de las propiedades
- Administración de listados de propiedades

## Características Principales

- **Sistema de Autenticación**: Registro e inicio de sesión de usuarios seguros.
- **Gestión de Propiedades**: Interfaz intuitiva para crear, editar y eliminar publicaciones de propiedades.
- **Galería de Imágenes**: Soporte para múltiples imágenes por propiedad.
- **Diseño Responsivo**: Experiencia óptima en dispositivos móviles y de escritorio.

## Requisitos del Sistema

- PHP >= 8.1
- Composer
- MySQL o SQLite
- Node.js y NPM para los assets
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalación

```bash
# Clonar el repositorio
git clone [url-del-repositorio] urbancms
cd urbancms

# Instalar dependencias PHP
composer install

# Instalar dependencias JavaScript
npm install

# Configurar variables de entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Compilar assets
npm run build

# Iniciar el servidor
php artisan serve
```

## Uso

1. Accede a la aplicación a través de la URL proporcionada por el servidor de desarrollo
2. Regístrate o inicia sesión con tus credenciales
3. Navega al panel de control para comenzar a crear publicaciones de propiedades
4. Añade los detalles e imágenes de la propiedad y publícala

## Planes Futuros

- Implementación de búsqueda avanzada de propiedades
- Sistema de mensajería entre usuarios
- Funcionalidades de reportes y estadísticas
- Integración con servicios de mapas

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).
