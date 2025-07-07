<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

trait HandlesImageProcessing
{
    /**
     * Convierte una imagen a formato WebP y la almacena con optimizaciones
     */
    protected function storeImageAsWebP(UploadedFile $file, string $directory = 'images', array $options = []): string
    {
        // Crear el manager de imágenes con el driver GD
        $manager = new ImageManager(new Driver());

        // Generar un nombre SEO-friendly para el archivo
        $filename = $this->generateSeoFilename($options['context'] ?? [], $directory);
        $path = $directory . '/' . $filename;

        // Leer y procesar la imagen
        $image = $manager->read($file);
        
        // Obtener las dimensiones máximas desde opciones o config
        $maxWidth = $options['max_width'] ?? config('image.max_width', 1200);
        $maxHeight = $options['max_height'] ?? config('image.max_height', 800);
        
        // Redimensionar si la imagen es muy grande
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convertir a WebP con calidad configurable
        $quality = $options['quality'] ?? config('image.quality', 85);
        $webpData = $image->toWebp($quality);

        // Guardar en storage
        Storage::disk('public')->put($path, (string) $webpData);

        return $path;
    }

    /**
     * Convierte una imagen existente desde storage a formato WebP (para comandos de conversión)
     */
    protected function convertExistingImageToWebP(string $existingPath, string $directory = 'images', array $options = []): string
    {
        // Crear el manager de imágenes con el driver GD
        $manager = new ImageManager(new Driver());

        // Para conversiones existentes, generar nombre simple para mantener compatibilidad
        $filename = uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        // Leer la imagen existente desde storage
        $imageContent = Storage::disk('public')->get($existingPath);
        $image = $manager->read($imageContent);
        
        // Obtener las dimensiones máximas desde opciones o config
        $maxWidth = $options['max_width'] ?? config('image.max_width', 1200);
        $maxHeight = $options['max_height'] ?? config('image.max_height', 800);
        
        // Redimensionar si la imagen es muy grande
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convertir a WebP con calidad configurable
        $quality = $options['quality'] ?? config('image.quality', 85);
        $webpData = $image->toWebp($quality);

        // Guardar en storage
        Storage::disk('public')->put($path, (string) $webpData);

        return $path;
    }

    /**
     * Elimina la imagen anterior si existe
     */
    protected function deleteOldImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * Procesa múltiples imágenes y las convierte a WebP
     */
    protected function processImages(array $imageFields, $request, $model = null, string $directory = 'images', array $options = []): array
    {
        $imageData = [];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Eliminar imagen anterior si existe (para actualización)
                if ($model && $model->$field) {
                    $this->deleteOldImage($model->$field);
                }
                
                // Preparar contexto para nombre SEO
                $contextOptions = $options;
                if (isset($options['context'])) {
                    $contextOptions['context']['field'] = $field;
                }
                
                // Guardar nueva imagen convertida a WebP
                $imageData[$field] = $this->storeImageAsWebP(
                    $request->file($field), 
                    $directory, 
                    $contextOptions
                );
            }
        }

        return $imageData;
    }

    /**
     * Genera un nombre SEO-friendly para las imágenes basado en el contexto
     */
    protected function generateSeoFilename(array $context = [], string $directory = 'images'): string
    {
        $parts = [];
        
        // Determinar el tipo de contenido basado en el contexto
        if (isset($context['type'])) {
            switch ($context['type']) {
                case 'property':
                    $parts = $this->generatePropertyImageName($context);
                    break;
                case 'article':
                    $parts = $this->generateArticleImageName($context);
                    break;
                default:
                    $parts = $this->generateGenericRealEstateImageName();
                    break;
            }
        } else {
            $parts = $this->generateGenericRealEstateImageName();
        }

        // Crear el nombre base
        $baseName = implode('-', array_filter($parts));
        $baseName = Str::slug($baseName, '-');
        
        // Limitar la longitud del nombre (máximo 100 caracteres sin extensión)
        if (strlen($baseName) > 100) {
            $baseName = substr($baseName, 0, 100);
        }

        // Generar nombre único para evitar duplicados
        $uniqueName = $this->ensureUniqueFilename($baseName, $directory);

        return $uniqueName . '.webp';
    }

    /**
     * Genera nombre para imágenes de propiedades
     */
    protected function generatePropertyImageName(array $context): array
    {
        $parts = [];

        // Tipo de propiedad basado en si es venta o renta
        if (isset($context['is_for_sale'])) {
            $parts[] = $context['is_for_sale'] ? 'casa-venta' : 'propiedad-renta';
        } else {
            $parts[] = 'propiedad';
        }

        // Ubicación (usar las líneas de ubicación disponibles)
        $locationParts = [];
        if (!empty($context['location_line1'])) {
            $locationParts[] = $context['location_line1'];
        }
        if (!empty($context['location_line2'])) {
            $locationParts[] = $context['location_line2'];
        }
        if (!empty($context['location_line3'])) {
            $locationParts[] = $context['location_line3'];
        }

        if (!empty($locationParts)) {
            $parts[] = implode('-', $locationParts);
        } else {
            $parts[] = 'ubicacion-premium';
        }

        // Características principales (usar las primeras características disponibles)
        $features = [];
        for ($i = 1; $i <= 8; $i++) {
            if (!empty($context["feature{$i}"])) {
                $features[] = $context["feature{$i}"];
                if (count($features) >= 2) break; // Máximo 2 características
            }
        }

        if (!empty($features)) {
            $parts[] = implode('-', $features);
        }

        return $parts;
    }

    /**
     * Genera nombre para imágenes de artículos de blog
     */
    protected function generateArticleImageName(array $context): array
    {
        $parts = ['blog'];

        // Título del artículo
        if (!empty($context['title'])) {
            $title = Str::limit($context['title'], 50, '');
            $parts[] = $title;
        } elseif (!empty($context['meta_title'])) {
            $title = Str::limit($context['meta_title'], 50, '');
            $parts[] = $title;
        } else {
            $parts[] = 'articulo-bienes-raices';
        }

        // Keywords (usar las primeras 2 si están disponibles)
        if (!empty($context['keywords'])) {
            $keywords = explode(',', $context['keywords']);
            $keywords = array_slice(array_map('trim', $keywords), 0, 2);
            if (!empty($keywords)) {
                $parts[] = implode('-', $keywords);
            }
        }

        return $parts;
    }

    /**
     * Genera nombre genérico profesional para bienes raíces
     */
    protected function generateGenericRealEstateImageName(): array
    {
        $genericTerms = [
            'propiedad-premium',
            'bienes-raices-profesional',
            'inmueble-exclusivo',
            'residencia-moderna',
            'hogar-ideal',
            'inversion-inmobiliaria',
            'casa-nueva',
            'departamento-lujo'
        ];

        return [
            $genericTerms[array_rand($genericTerms)],
            'lugar-para-vivir'
        ];
    }

    /**
     * Asegura que el nombre del archivo sea único
     */
    protected function ensureUniqueFilename(string $baseName, string $directory): string
    {
        $uniqueId = uniqid();
        $fullName = $baseName . '-' . $uniqueId;
        
        // Verificar si ya existe un archivo con este nombre
        $counter = 1;
        $originalFullName = $fullName;
        
        while (Storage::disk('public')->exists($directory . '/' . $fullName . '.webp')) {
            $fullName = $originalFullName . '-' . $counter;
            $counter++;
            
            // Protección contra bucle infinito
            if ($counter > 1000) {
                $fullName = $originalFullName . '-' . time() . '-' . rand(1000, 9999);
                break;
            }
        }

        return $fullName;
    }
}
