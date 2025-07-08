# Nombres SEO-Friendly para Imágenes

## Descripción
Esta funcionalidad genera automáticamente nombres SEO-friendly para las imágenes subidas tanto en propiedades como en artículos del blog, mejorando el posicionamiento SEO y la profesionalidad del sitio.

## Características

### 🏠 Propiedades
Los nombres de las imágenes de propiedades se generan basándose en:
- **Tipo de transacción**: `casa-venta` o `propiedad-renta`
- **Ubicación**: Combinación de `location_line1`, `location_line2`, `location_line3`
- **Características**: Hasta 2 características principales de la propiedad
- **Identificador único**: Para evitar duplicados

**Ejemplos**:
```
casa-venta-colonia-centro-guadalajara-recamara-principal-60f8a2b1e4.webp
propiedad-renta-zona-residencial-balcon-terraza-60f8a2b1e5.webp
casa-venta-ubicacion-premium-cocina-integral-60f8a2b1e6.webp
```

### 📝 Artículos del Blog
Los nombres de las imágenes de artículos se generan basándose en:
- **Prefijo**: `blog`
- **Título**: Título del artículo (limitado a 50 caracteres)
- **Keywords**: Primeras 2 palabras clave del artículo
- **Identificador único**: Para evitar duplicados

**Ejemplos**:
```
blog-mejores-inversiones-bienes-raices-2025-inversion-propiedades-60f8a2b1e7.webp
blog-guia-compra-casa-credito-hipotecario-60f8a2b1e8.webp
blog-articulo-bienes-raices-mercado-inmobiliario-60f8a2b1e9.webp
```

### 🔄 Términos Genéricos
Cuando no hay suficiente información contextual, se usan términos profesionales:
- `propiedad-premium`
- `bienes-raices-profesional`
- `inmueble-exclusivo`
- `residencia-moderna`
- `hogar-ideal`
- `inversion-inmobiliaria`
- `casa-nueva`
- `departamento-lujo`

## Ventajas SEO

1. **Descriptivos**: Los nombres describen el contenido de la imagen
2. **Palabras clave**: Incluyen términos relevantes para bienes raíces
3. **URL-friendly**: Sin caracteres especiales, acentos o espacios
4. **Únicos**: Cada imagen tiene un nombre único para evitar conflictos
5. **Profesionales**: Mantienen un estándar profesional del sector inmobiliario

## Implementación Técnica

### Trait: `HandlesImageProcessing`
```php
// Método principal que genera nombres SEO
protected function generateSeoFilename(array $context = [], string $directory = 'images'): string

// Métodos específicos para cada tipo de contenido
protected function generatePropertyImageName(array $context): array
protected function generateArticleImageName(array $context): array
protected function generateGenericRealEstateImageName(): array

// Asegura nombres únicos
protected function ensureUniqueFilename(string $baseName, string $directory): string
```

### Controladores Actualizados
- `PropertyController`: Pasa contexto de ubicación, tipo de venta y características
- `ArticleController`: Pasa contexto de título, meta_title y keywords

### Limitaciones Técnicas
- **Longitud máxima**: 100 caracteres base + identificador único
- **Caracteres permitidos**: Solo letras, números y guiones
- **Formato final**: Siempre `.webp` para optimización
- **Verificación de duplicados**: Hasta 1000 intentos, luego usa timestamp + random

## Compatibilidad

### ✅ Funciona con:
- Creación de nuevas propiedades
- Actualización de propiedades existentes
- Creación de nuevos artículos
- Actualización de artículos existentes
- Comando de conversión de imágenes existentes

### ⚠️ Consideraciones:
- Las imágenes existentes mantienen sus nombres hasta que se reconviertan
- El comando `php artisan images:convert-to-webp` usa nombres simples para compatibilidad
- Los tests están incluidos para verificar la funcionalidad

## Uso en Desarrollo

### Ejecutar Tests
```bash
# Tests unitarios del trait
php artisan test tests/Unit/HandlesImageProcessingTest.php

# Tests de integración
php artisan test tests/Feature/SeoImageNamesIntegrationTest.php

# Todos los tests relacionados
php artisan test --filter="SeoImage"
```

### Debugging
Para verificar los nombres generados, puedes agregar logs temporales:
```php
Log::info('Generated SEO filename: ' . $filename, $context);
```

## Beneficios para el Cliente

1. **Mejor SEO**: Las imágenes contribuyen al posicionamiento del sitio
2. **Profesionalismo**: Los nombres reflejan el contenido de manera clara
3. **Organización**: Fácil identificación de imágenes en el servidor
4. **Rendimiento**: Mantiene la optimización WebP existente
5. **Escalabilidad**: Sistema robusto que evita conflictos de nombres

## Futuras Mejoras

- [ ] Integración con alt text automático
- [ ] Soporte para múltiples idiomas
- [ ] Análisis de keywords más avanzado
- [ ] Integración con herramientas de SEO externas
