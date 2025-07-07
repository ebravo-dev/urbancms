<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait HandlesImageProcessing
{
    /**
     * Convierte una imagen a formato WebP y la almacena con optimizaciones
     */
    protected function storeImageAsWebP(UploadedFile $file, string $directory = 'images', array $options = []): string
    {
        // Crear el manager de imágenes con el driver GD
        $manager = new ImageManager(new Driver());

        // Generar un nombre único para el archivo
        $filename = uniqid() . '.webp';
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
                
                // Guardar nueva imagen convertida a WebP
                $imageData[$field] = $this->storeImageAsWebP(
                    $request->file($field), 
                    $directory, 
                    $options
                );
            }
        }

        return $imageData;
    }
}
