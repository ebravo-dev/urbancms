<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeoImageNamesIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_creates_seo_friendly_names_when_creating_property()
    {
        // Crear imagen falsa para el test
        $image = UploadedFile::fake()->image('test-property.jpg', 800, 600);

        $propertyData = [
            'is_for_sale' => true,
            'location_line1' => 'Centro Histórico',
            'location_line2' => 'Guadalajara',
            'location_line3' => 'Jalisco',
            'feature1' => 'Balcón Grande',
            'feature2' => 'Cocina Moderna',
            'image1' => $image,
        ];

        $response = $this->withoutMiddleware()->post(route('properties.store'), $propertyData);

        $response->assertStatus(302); // Redirect después de crear
        
        // Verificar que la propiedad se creó
        $property = Property::first();
        $this->assertNotNull($property);
        
        // Verificar que el nombre de la imagen es SEO-friendly
        $imagePath = $property->image1;
        $this->assertNotNull($imagePath);
        
        $filename = basename($imagePath);
        
        // Verificar elementos SEO en el nombre
        $this->assertStringContainsString('casa-venta', $filename);
        $this->assertStringContainsString('centro-historico', $filename);
        $this->assertStringContainsString('guadalajara', $filename);
        $this->assertStringEndsWith('.webp', $filename);
        
        // Verificar que el archivo realmente existe
        $this->assertTrue(Storage::disk('public')->exists($imagePath));
    }

    /** @test */
    public function it_creates_different_names_for_multiple_images_same_property()
    {
        $image1 = UploadedFile::fake()->image('test1.jpg', 800, 600);
        $image2 = UploadedFile::fake()->image('test2.jpg', 800, 600);

        $propertyData = [
            'is_for_sale' => false, // Renta
            'location_line1' => 'Zona Rosa',
            'feature1' => 'Terraza',
            'image1' => $image1,
            'image2' => $image2,
        ];

        $response = $this->withoutMiddleware()->post(route('properties.store'), $propertyData);
        $response->assertStatus(302);
        
        $property = Property::first();
        
        // Ambas imágenes deben tener nombres diferentes
        $this->assertNotEquals($property->image1, $property->image2);
        
        // Pero ambas deben contener elementos SEO similares
        $filename1 = basename($property->image1);
        $filename2 = basename($property->image2);
        
        $this->assertStringContainsString('propiedad-renta', $filename1);
        $this->assertStringContainsString('propiedad-renta', $filename2);
        $this->assertStringContainsString('zona-rosa', $filename1);
        $this->assertStringContainsString('zona-rosa', $filename2);
        
        // Verificar que ambos archivos existen
        $this->assertTrue(Storage::disk('public')->exists($property->image1));
        $this->assertTrue(Storage::disk('public')->exists($property->image2));
    }

    /** @test */
    public function it_generates_generic_names_when_minimal_property_data()
    {
        $image = UploadedFile::fake()->image('minimal.jpg', 800, 600);

        $propertyData = [
            'is_for_sale' => true,
            'image1' => $image,
            // Sin ubicación ni características
        ];

        $response = $this->withoutMiddleware()->post(route('properties.store'), $propertyData);
        $response->assertStatus(302);
        
        $property = Property::first();
        $filename = basename($property->image1);
        
        // Debe contener términos genéricos
        $this->assertStringContainsString('casa-venta', $filename);
        $this->assertStringContainsString('ubicacion-premium', $filename);
        $this->assertStringEndsWith('.webp', $filename);
        
        $this->assertTrue(Storage::disk('public')->exists($property->image1));
    }
}
