<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    /**
     * The Pexels API key.
     *
     * @var string
     */
    protected $apiKey = 'hUgNtiggz7bPJuPwKSIvNlfLrO33fccMfIMJAUC7VuovnA8PS2OWmWfd'; // This is stored as-is, but will be used as a header

    /**
     * Seed the properties table with example data.
     */
    public function run(): void
    {
        $this->command->info('Creating example properties...');

        // Create 5 original example properties
        $this->createResidentialProperty();
        $this->createCommercialProperty();
        $this->createLuxuryApartment();
        $this->createVacationHome();
        $this->createLandProperty();

        // Create 7 additional properties
        $this->createModernLoft();
        $this->createHistoricHouse();
        $this->createRetailSpace();
        $this->createIndustrialWarehouse();
        $this->createMountainCabin();
        $this->createBeachCondo();
        $this->createStudentStudio();

        $this->command->info('Properties created successfully!');
    }

    /**
     * Fetch images from Pexels API.
     *
     * @param string $query The search term
     * @param int $count Number of images to fetch
     * @return array Array of image URLs
     */
    protected function fetchImagesFromPexels(string $query, int $count = 4): array
    {
        return [];
        try {
            // Using the API key as a header value as per Pexels API documentation
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get("https://api.pexels.com/v1/search", [
                'query' => $query,
                'per_page' => $count,
            ]);

            $this->command->info("Pexels API response status: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $urls = [];

                if (isset($data['photos']) && is_array($data['photos'])) {
                    foreach ($data['photos'] as $photo) {
                        // Use the large sized image for better quality
                        if (isset($photo['src']['large'])) {
                            $urls[] = $photo['src']['large'];
                        }

                        // If we have enough URLs, break the loop
                        if (count($urls) >= $count) {
                            break;
                        }
                    }
                } else {
                    $this->command->info("No photos found in Pexels API response");
                }

                return $urls;
            }

            $this->command->error('Failed to fetch images from Pexels API: ' . $response->status());
            if ($response->status() === 401) {
                $this->command->info('Check if the API key is correct and properly formatted');
            }

            // Log the full response for debugging
            $this->command->info("Response body: " . $response->body());

            return [];
        } catch (\Exception $e) {
            $this->command->error('Error connecting to Pexels API: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a residential property example.
     */
    protected function createResidentialProperty(): void
    {
        // Fetch residential property images
        $images = $this->fetchImagesFromPexels('modern house residential', 4);

        // If we couldn't get images from the API, use placeholder URLs
        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg',
                'https://images.pexels.com/photos/1643384/pexels-photo-1643384.jpeg',
                'https://images.pexels.com/photos/1029599/pexels-photo-1029599.jpeg',
                'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Av. Insurgentes Sur 1234',
            'location_line2' => 'Col. Del Valle',
            'location_line3' => 'Ciudad de México, CP 03100',
            'google_maps_url' => 'https://www.google.com/maps?q=19.3731853,-99.1748183',
            'feature1' => '3 Habitaciones',
            'feature2' => '2 Baños completos',
            'feature3' => 'Cocina equipada',
            'feature4' => 'Sala de estar amplia',
            'feature5' => 'Comedor para 6 personas',
            'feature6' => '2 Cajones de estacionamiento',
            'feature7' => 'Área de lavado',
            'feature8' => 'Jardín trasero',
            'investment' => 4500000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a commercial property example.
     */
    protected function createCommercialProperty(): void
    {
        $images = $this->fetchImagesFromPexels('office commercial building', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/3182826/pexels-photo-3182826.jpeg',
                'https://images.pexels.com/photos/1170412/pexels-photo-1170412.jpeg',
                'https://images.pexels.com/photos/267507/pexels-photo-267507.jpeg',
                'https://images.pexels.com/photos/260689/pexels-photo-260689.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => false, // For rent
            'location_line1' => 'Av. Paseo de la Reforma 222',
            'location_line2' => 'Col. Juárez',
            'location_line3' => 'Ciudad de México, CP 06600',
            'google_maps_url' => 'https://www.google.com/maps?q=19.4278586,-99.1674805',
            'feature1' => 'Área de 120m²',
            'feature2' => 'Recepción',
            'feature3' => '3 Oficinas privadas',
            'feature4' => 'Sala de juntas',
            'feature5' => 'Cocina/comedor',
            'feature6' => 'Baños para damas y caballeros',
            'feature7' => '2 Cajones de estacionamiento',
            'feature8' => 'Seguridad 24/7',
            'investment' => 35000.00, // Monthly rent
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a luxury apartment example.
     */
    protected function createLuxuryApartment(): void
    {
        $images = $this->fetchImagesFromPexels('luxury apartment penthouse', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg',
                'https://images.pexels.com/photos/276554/pexels-photo-276554.jpeg',
                'https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg',
                'https://images.pexels.com/photos/275484/pexels-photo-275484.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Av. Presidente Masaryk 111',
            'location_line2' => 'Col. Polanco',
            'location_line3' => 'Ciudad de México, CP 11560',
            'google_maps_url' => 'https://www.google.com/maps?q=19.4316191,-99.1934799',
            'feature1' => 'Penthouse de 250m²',
            'feature2' => '3 Habitaciones con baño',
            'feature3' => 'Terraza panorámica de 100m²',
            'feature4' => 'Cocina gourmet equipada',
            'feature5' => 'Sala y comedor de lujo',
            'feature6' => '3 Cajones de estacionamiento',
            'feature7' => 'Gimnasio y alberca en el edificio',
            'feature8' => 'Seguridad y conserje 24/7',
            'investment' => 12500000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a vacation home example.
     */
    protected function createVacationHome(): void
    {
        $images = $this->fetchImagesFromPexels('beach house vacation home', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg',
                'https://images.pexels.com/photos/261327/pexels-photo-261327.jpeg',
                'https://images.pexels.com/photos/261395/pexels-photo-261395.jpeg',
                'https://images.pexels.com/photos/271643/pexels-photo-271643.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => false, // For rent
            'location_line1' => 'Calle Playa Azul 101',
            'location_line2' => 'Zona Hotelera',
            'location_line3' => 'Cancún, Quintana Roo, CP 77500',
            'google_maps_url' => 'https://www.google.com/maps?q=21.0726757,-86.7765897',
            'feature1' => 'Villa frente al mar',
            'feature2' => '4 Habitaciones con vista al mar',
            'feature3' => '4.5 Baños',
            'feature4' => 'Alberca infinita privada',
            'feature5' => 'Terraza con BBQ',
            'feature6' => 'Acceso directo a la playa',
            'feature7' => 'Personal de servicio incluido',
            'feature8' => 'Capacidad para 10 personas',
            'investment' => 12000.00, // Daily rate
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a land property example.
     */
    protected function createLandProperty(): void
    {
        $images = $this->fetchImagesFromPexels('land for sale countryside', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/842711/pexels-photo-842711.jpeg',
                'https://images.pexels.com/photos/440731/pexels-photo-440731.jpeg',
                'https://images.pexels.com/photos/158179/lake-constance-sheep-pasture-sunset-158179.jpeg',
                'https://images.pexels.com/photos/347141/pexels-photo-347141.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Carretera Federal Km 50',
            'location_line2' => 'Ejido San Miguel',
            'location_line3' => 'Valle de Bravo, Estado de México',
            'google_maps_url' => 'https://www.google.com/maps?q=19.2464698,-100.1534361',
            'feature1' => 'Terreno de 5,000m²',
            'feature2' => 'Uso de suelo mixto',
            'feature3' => 'Servicios a pie de terreno',
            'feature4' => 'Vista panorámica',
            'feature5' => 'Acceso pavimentado',
            'feature6' => 'A 10 minutos del centro',
            'feature7' => 'Ideal para desarrollo residencial',
            'feature8' => 'Documentación en regla',
            'investment' => 2800000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a modern loft property example.
     */
    protected function createModernLoft(): void
    {
        $images = $this->fetchImagesFromPexels('modern loft apartment', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/1918291/pexels-photo-1918291.jpeg',
                'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg',
                'https://images.pexels.com/photos/275484/pexels-photo-275484.jpeg',
                'https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Av. Chapultepec 500',
            'location_line2' => 'Col. Roma Norte',
            'location_line3' => 'Ciudad de México, CP 06700',
            'google_maps_url' => 'https://www.google.com/maps?q=19.4176371,-99.1631208',
            'feature1' => 'Loft de 120m²',
            'feature2' => 'Techos altos de 4m',
            'feature3' => 'Ventanales de piso a techo',
            'feature4' => 'Cocina integrada tipo isla',
            'feature5' => 'Acabados industriales',
            'feature6' => '1 Recámara tipo mezzanine',
            'feature7' => '2 Baños completos',
            'feature8' => 'Estacionamiento para 1 auto',
            'investment' => 5800000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a historic house property example.
     */
    protected function createHistoricHouse(): void
    {
        $images = $this->fetchImagesFromPexels('historic colonial house', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/1029599/pexels-photo-1029599.jpeg',
                'https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg',
                'https://images.pexels.com/photos/2079246/pexels-photo-2079246.jpeg',
                'https://images.pexels.com/photos/2635038/pexels-photo-2635038.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Calle 59 #510',
            'location_line2' => 'Centro Histórico',
            'location_line3' => 'Mérida, Yucatán, CP 97000',
            'google_maps_url' => 'https://www.google.com/maps?q=20.9673702,-89.5925857',
            'feature1' => 'Casona colonial de 350m²',
            'feature2' => 'Arquitectura del siglo XIX',
            'feature3' => '4 Recámaras con baño',
            'feature4' => 'Patio central con fuente',
            'feature5' => 'Techos de 6m con vigas originales',
            'feature6' => 'Pisos de pasta',
            'feature7' => 'Alberca y jardín trasero',
            'feature8' => 'Completamente restaurada',
            'investment' => 8500000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a retail space property example.
     */
    protected function createRetailSpace(): void
    {
        $images = $this->fetchImagesFromPexels('retail store shop', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/264507/pexels-photo-264507.jpeg',
                'https://images.pexels.com/photos/264635/pexels-photo-264635.jpeg',
                'https://images.pexels.com/photos/1667088/pexels-photo-1667088.jpeg',
                'https://images.pexels.com/photos/3184357/pexels-photo-3184357.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => false, // For rent
            'location_line1' => 'Plaza Andares, Local B-15',
            'location_line2' => 'Blvd. Puerta de Hierro 4965',
            'location_line3' => 'Zapopan, Jalisco, CP 45116',
            'google_maps_url' => 'https://www.google.com/maps?q=20.7102783,-103.4103589',
            'feature1' => 'Local comercial de 85m²',
            'feature2' => 'Frente de 8m con escaparate',
            'feature3' => 'Ubicación de alto tráfico peatonal',
            'feature4' => 'Aire acondicionado',
            'feature5' => 'Medio baño',
            'feature6' => 'Bodega trasera de 15m²',
            'feature7' => 'Instalaciones eléctricas certificadas',
            'feature8' => 'Seguridad 24/7 en plaza',
            'investment' => 45000.00, // Monthly rent
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create an industrial warehouse property example.
     */
    protected function createIndustrialWarehouse(): void
    {
        $images = $this->fetchImagesFromPexels('industrial warehouse', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/236705/pexels-photo-236705.jpeg',
                'https://images.pexels.com/photos/3800060/pexels-photo-3800060.jpeg',
                'https://images.pexels.com/photos/4481326/pexels-photo-4481326.jpeg',
                'https://images.pexels.com/photos/3995913/pexels-photo-3995913.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Parque Industrial El Marqués',
            'location_line2' => 'Carretera 57, Km 196',
            'location_line3' => 'Querétaro, CP 76246',
            'google_maps_url' => 'https://www.google.com/maps?q=20.7160732,-100.4486038',
            'feature1' => 'Nave industrial de 2,500m²',
            'feature2' => 'Altura libre de 12m',
            'feature3' => '5 andenes de carga',
            'feature4' => 'Oficinas en 2 niveles de 300m²',
            'feature5' => 'Subestación eléctrica de 500 KVA',
            'feature6' => 'Sistema contra incendios',
            'feature7' => 'Patio de maniobras para tráilers',
            'feature8' => 'Certificación LEED Silver',
            'investment' => 18500000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a mountain cabin property example.
     */
    protected function createMountainCabin(): void
    {
        $images = $this->fetchImagesFromPexels('mountain cabin wood', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/803975/pexels-photo-803975.jpeg',
                'https://images.pexels.com/photos/147411/pexels-photo-147411.jpeg',
                'https://images.pexels.com/photos/1497232/pexels-photo-1497232.jpeg',
                'https://images.pexels.com/photos/731082/pexels-photo-731082.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => false, // For rent
            'location_line1' => 'Bosques de Monterreal',
            'location_line2' => 'Km 3 Camino a las Cabañas',
            'location_line3' => 'Arteaga, Coahuila, CP 25350',
            'google_maps_url' => 'https://www.google.com/maps?q=25.3518141,-100.6040954',
            'feature1' => 'Cabaña de madera de 120m²',
            'feature2' => '3 Recámaras con vista al bosque',
            'feature3' => '2 Baños completos',
            'feature4' => 'Chimenea de leña',
            'feature5' => 'Cocina equipada',
            'feature6' => 'Terraza con asador',
            'feature7' => 'Estacionamiento para 2 autos',
            'feature8' => 'A 5 min de pistas de esquí',
            'investment' => 3500.00, // Daily rate
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a beach condo property example.
     */
    protected function createBeachCondo(): void
    {
        $images = $this->fetchImagesFromPexels('beach condo apartment', 4);

        if (empty($images)) {
            $images = [
                'https://images.pexels.com/photos/2119714/pexels-photo-2119714.jpeg',
                'https://images.pexels.com/photos/1428348/pexels-photo-1428348.jpeg',
                'https://images.pexels.com/photos/3356416/pexels-photo-3356416.jpeg',
                'https://images.pexels.com/photos/2988860/pexels-photo-2988860.jpeg',
            ];
        }

        Property::create([
            'is_for_sale' => true,
            'location_line1' => 'Torre Oceana, Departamento 802',
            'location_line2' => 'Blvd. Miguel de la Madrid',
            'location_line3' => 'Manzanillo, Colima, CP 28218',
            'google_maps_url' => 'https://www.google.com/maps?q=19.1172862,-104.3494985',
            'feature1' => 'Condominio frente al mar de 150m²',
            'feature2' => '3 Recámaras, 2 con vista al mar',
            'feature3' => '3 Baños completos',
            'feature4' => 'Cocina equipada con isla',
            'feature5' => 'Sala-comedor con balcón panorámico',
            'feature6' => '2 Cajones de estacionamiento',
            'feature7' => 'Alberca, spa y gimnasio',
            'feature8' => 'Control de acceso y seguridad 24/7',
            'investment' => 6800000.00,
            'image1' => $images[0] ?? null,
            'image2' => $images[1] ?? null,
            'image3' => $images[2] ?? null,
            'image4' => $images[3] ?? null,
        ]);
    }

    /**
     * Create a student studio property example.
     */
    protected function createStudentStudio(): void
    {
        // $images = $this->fetchImagesFromPexels('small studio apartment', 4);

        // if (empty($images)) {
        //     $images = [
        //         'https://images.pexels.com/photos/439227/pexels-photo-439227.jpeg',
        //         'https://images.pexels.com/photos/1082355/pexels-photo-1082355.jpeg',
        //         'https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg',
        //         'https://images.pexels.com/photos/2062426/pexels-photo-2062426.jpeg',
        //     ];
        // }

        // Property::create([
        //     'is_for_sale' => false, // For rent
        //     'location_line1' => 'Calle Río Consulado 234',
        //     'location_line2' => 'Col. San Lorenzo',
        //     'location_line3' => 'Puebla, Puebla, CP 72410',
        //     'google_maps_url' => 'https://www.google.com/maps?q=19.0521994,-98.2046584',
        //     'feature1' => 'Estudio amueblado de 35m²',
        //     'feature2' => 'Cama matrimonial',
        //     'feature3' => 'Kitchenette equipada',
        //     'feature4' => 'Baño completo',
        //     'feature5' => 'Internet de alta velocidad incluido',
        //     'feature6' => 'Área de estudio',
        //     'feature7' => 'A 5 minutos caminando de BUAP',
        //     'feature8' => 'Servicios incluidos en la renta',
        //     'investment' => 6000.00, // Monthly rent
        //     'image1' => $images[0] ?? null,
        //     'image2' => $images[1] ?? null,
        //     'image3' => $images[2] ?? null,
        //     'image4' => $images[3] ?? null,
        // ]);
    }
}
