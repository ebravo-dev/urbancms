<?php

namespace Tests\Unit;

use App\Traits\HandlesImageProcessing;
use Tests\TestCase;
use Illuminate\Support\Str;

class HandlesImageProcessingTest extends TestCase
{
    use HandlesImageProcessing;

    /** @test */
    public function it_generates_seo_friendly_property_names()
    {
        $context = [
            'type' => 'property',
            'is_for_sale' => true,
            'location_line1' => 'Colonia Centro',
            'location_line2' => 'Guadalajara',
            'location_line3' => 'Jalisco',
            'feature1' => 'Recámara Principal',
            'feature2' => 'Cocina Integral',
            'feature3' => 'Jardín',
        ];

        $filename = $this->generateSeoFilename($context, 'test-images');
        
        // Verificar que contiene elementos esperados
        $this->assertStringContainsString('casa-venta', $filename);
        $this->assertStringContainsString('colonia-centro', $filename);
        $this->assertStringContainsString('guadalajara', $filename);
        $this->assertStringContainsString('recamara-principal', $filename);
        $this->assertStringEndsWith('.webp', $filename);
        
        // Verificar que es un slug válido
        $this->assertEquals($filename, Str::slug(str_replace('.webp', '', $filename)) . '.webp');
    }

    /** @test */
    public function it_generates_seo_friendly_rental_property_names()
    {
        $context = [
            'type' => 'property',
            'is_for_sale' => false,
            'location_line1' => 'Zona Residencial',
            'feature1' => 'Balcón',
        ];

        $filename = $this->generateSeoFilename($context, 'test-images');
        
        $this->assertStringContainsString('propiedad-renta', $filename);
        $this->assertStringContainsString('zona-residencial', $filename);
        $this->assertStringContainsString('balcon', $filename);
        $this->assertStringEndsWith('.webp', $filename);
    }

    /** @test */
    public function it_generates_seo_friendly_article_names()
    {
        $context = [
            'type' => 'article',
            'title' => 'Mejores Inversiones en Bienes Raíces 2025',
            'keywords' => 'inversión, propiedades, bienes raíces',
        ];

        $filename = $this->generateSeoFilename($context, 'test-images');
        
        $this->assertStringContainsString('blog', $filename);
        $this->assertStringContainsString('mejores-inversiones', $filename);
        $this->assertStringContainsString('inversion', $filename);
        $this->assertStringContainsString('propiedades', $filename);
        $this->assertStringEndsWith('.webp', $filename);
    }

    /** @test */
    public function it_generates_generic_names_when_no_context()
    {
        $filename = $this->generateSeoFilename([], 'test-images');
        
        $this->assertStringContainsString('lugar-para-vivir', $filename);
        $this->assertStringEndsWith('.webp', $filename);
        
        // Debe contener uno de los términos genéricos
        $hasGenericTerm = false;
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
        
        foreach ($genericTerms as $term) {
            if (str_contains($filename, $term)) {
                $hasGenericTerm = true;
                break;
            }
        }
        
        $this->assertTrue($hasGenericTerm, 'El nombre debe contener al menos un término genérico');
    }

    /** @test */
    public function it_limits_filename_length()
    {
        $context = [
            'type' => 'property',
            'is_for_sale' => true,
            'location_line1' => 'Una ubicación con un nombre extremadamente largo que debería ser truncado para evitar problemas',
            'location_line2' => 'Otra línea de ubicación muy larga',
            'location_line3' => 'Y una tercera línea también muy larga',
            'feature1' => 'Característica número uno con nombre muy largo',
            'feature2' => 'Característica número dos también con nombre extenso',
        ];

        $filename = $this->generateSeoFilename($context, 'test-images');
        
        // El nombre completo (sin .webp) no debe exceder significativamente los 100 caracteres
        $nameWithoutExtension = str_replace('.webp', '', $filename);
        $this->assertLessThanOrEqual(150, strlen($nameWithoutExtension), 'El nombre es demasiado largo');
    }

    /** @test */
    public function it_handles_special_characters_and_accents()
    {
        $context = [
            'type' => 'property',
            'is_for_sale' => true,
            'location_line1' => 'Colón & Niños Héroes',
            'feature1' => 'Baño con Tïna',
            'feature2' => 'Área Común'
        ];

        $filename = $this->generateSeoFilename($context, 'test-images');
        
        // Verificar que no hay caracteres especiales problemáticos
        $this->assertDoesNotMatchRegularExpression('/[&áéíóúñü]/i', $filename);
        $this->assertStringContainsString('colon', $filename);
        $this->assertStringContainsString('ninos-heroes', $filename);
        $this->assertStringContainsString('bano-con-tina', $filename);
        $this->assertStringContainsString('area-comun', $filename);
    }

    /** @test */
    public function it_ensures_unique_filenames()
    {
        $context = [
            'type' => 'property',
            'is_for_sale' => true,
            'location_line1' => 'Test Location',
        ];

        // Generar múltiples nombres con el mismo contexto
        $filename1 = $this->generateSeoFilename($context, 'test-images');
        $filename2 = $this->generateSeoFilename($context, 'test-images');
        $filename3 = $this->generateSeoFilename($context, 'test-images');

        // Todos deben ser diferentes (debido al uniqid)
        $this->assertNotEquals($filename1, $filename2);
        $this->assertNotEquals($filename2, $filename3);
        $this->assertNotEquals($filename1, $filename3);
        
        // Pero deben tener la misma base
        $this->assertStringContainsString('casa-venta', $filename1);
        $this->assertStringContainsString('casa-venta', $filename2);
        $this->assertStringContainsString('casa-venta', $filename3);
        $this->assertStringContainsString('test-location', $filename1);
        $this->assertStringContainsString('test-location', $filename2);
        $this->assertStringContainsString('test-location', $filename3);
    }
}
