<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(2),
            'publication_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'content' => $this->generateRealEstateContent(),
            'meta_title' => $title,
            'meta_description' => $this->faker->paragraph(1),
            'keywords' => $this->generatePropertyKeywords(),
        ];
    }

    /**
     * Generate realistic real estate content in JSON format
     */
    private function generateRealEstateContent(): array
    {
        $contentBlocks = [];

        // Títulos relacionados con bienes raíces
        $propertyTitles = [
            'Guía Completa para Comprar tu Primera Casa',
            'Tendencias del Mercado Inmobiliario 2025',
            'Cómo Evaluar el Valor de una Propiedad',
            'Consejos para Invertir en Bienes Raíces',
            'Los Mejores Barrios para Vivir en la Ciudad',
            'Renovaciones que Aumentan el Valor de tu Casa',
            'Proceso de Financiamiento Hipotecario',
            'Ventajas de Comprar vs Rentar una Propiedad'
        ];

        // Siempre empezar con un título principal
        $contentBlocks[] = [
            'type' => 'heading',
            'content' => $this->faker->randomElement($propertyTitles)
        ];

        // Párrafo introductorio
        $introTopics = [
            'El mercado inmobiliario actual presenta oportunidades únicas para compradores e inversionistas. En este artículo exploraremos las mejores estrategias para aprovechar estas condiciones favorables.',
            'La inversión en bienes raíces sigue siendo una de las formas más seguras y rentables de hacer crecer tu patrimonio. Te explicamos todo lo que necesitas saber.',
            'Comprar una propiedad es una de las decisiones financieras más importantes en la vida. Aquí te guiamos paso a paso en este proceso.',
            'El sector inmobiliario ha experimentado cambios significativos en los últimos años. Analizamos las tendencias actuales y proyecciones futuras.',
            'La ubicación sigue siendo el factor más importante al elegir una propiedad. Descubre cómo identificar las mejores zonas de inversión.'
        ];

        $contentBlocks[] = [
            'type' => 'paragraph',
            'content' => $this->faker->randomElement($introTopics)
        ];

        // Agregar subtítulos y contenido relevante
        $subtitles = [
            'Factores Clave a Considerar',
            'Análisis del Mercado Local',
            'Aspectos Financieros Importantes',
            'Consejos de Expertos',
            'Tendencias Actuales',
            'Ubicación y Conectividad',
            'Proyecciones de Valor'
        ];

        $propertyContent = [
            'La ubicación es fundamental al evaluar una propiedad. Considera la proximidad a transporte público, escuelas, centros comerciales y servicios médicos.',
            'El estado de la construcción y las instalaciones determina los costos futuros de mantenimiento. Una inspección profesional es altamente recomendada.',
            'Las tasas de interés hipotecario pueden variar significativamente. Es importante comparar ofertas de diferentes instituciones financieras.',
            'El crecimiento poblacional y el desarrollo urbano de la zona influyen directamente en la apreciación del valor de la propiedad.',
            'La seguridad del barrio y la calidad de vida son aspectos que impactan tanto en el bienestar familiar como en el valor de reventa.',
            'Los servicios públicos como agua, electricidad, gas y internet de calidad son esenciales para cualquier propiedad moderna.',
            'La plusvalía histórica de la zona te dará una idea del potencial de crecimiento de tu inversión a largo plazo.'
        ];

        // Añadir 2-4 secciones más
        $sectionsCount = $this->faker->numberBetween(2, 4);
        for ($i = 0; $i < $sectionsCount; $i++) {
            // Subtítulo
            $contentBlocks[] = [
                'type' => 'subtitle',
                'content' => $this->faker->randomElement($subtitles)
            ];

            // Párrafo de contenido
            $contentBlocks[] = [
                'type' => 'paragraph',
                'content' => $this->faker->randomElement($propertyContent)
            ];

            // Ocasionalmente agregar una cita
            if ($this->faker->boolean(30)) {
                $quotes = [
                    'La mejor inversión en la tierra es la tierra misma.',
                    'No esperes a comprar bienes raíces, compra bienes raíces y espera.',
                    'La ubicación, ubicación, ubicación. Los tres factores más importantes en bienes raíces.',
                    'Una casa no es solo una estructura, es un hogar donde se crean recuerdos.'
                ];

                $contentBlocks[] = [
                    'type' => 'quote',
                    'content' => $this->faker->randomElement($quotes)
                ];
            }

            // Ocasionalmente agregar una lista
            if ($this->faker->boolean(25)) {
                $lists = [
                    "Documentos necesarios para financiamiento\nIdentificación oficial\nComprobante de ingresos\nEstado de cuenta bancario\nHistorial crediticio",
                    "Características a evaluar en una propiedad\nEstructura y cimentación\nInstalaciones eléctricas\nSistema de plomería\nAislamiento térmico\nSeguridad del inmueble",
                    "Gastos adicionales al comprar una casa\nImpuestos de transferencia\nGastos notariales\nSeguro de la propiedad\nComisiones inmobiliarias\nInspecciones profesionales"
                ];

                $contentBlocks[] = [
                    'type' => 'list',
                    'content' => $this->faker->randomElement($lists)
                ];
            }
        }

        // Párrafo de conclusión
        $conclusions = [
            'La inversión en bienes raíces requiere paciencia y análisis cuidadoso, pero puede generar excelentes retornos a largo plazo.',
            'Recuerda siempre consultar con profesionales del sector antes de tomar decisiones importantes sobre propiedades.',
            'El mercado inmobiliario ofrece oportunidades para todos los presupuestos, lo importante es hacer la investigación adecuada.',
            'Una decisión bien informada en bienes raíces puede cambiar positivamente tu futuro financiero.',
            'La clave del éxito en inversiones inmobiliarias está en la educación continua y el análisis del mercado.'
        ];

        $contentBlocks[] = [
            'type' => 'paragraph',
            'content' => $this->faker->randomElement($conclusions)
        ];

        return $contentBlocks; // Retornar array directamente, no JSON string
    }

    /**
     * Generate property-related keywords
     */
    private function generatePropertyKeywords(): string
    {
        $keywordGroups = [
            ['bienes raíces', 'propiedades', 'inmobiliaria', 'casa'],
            ['inversión', 'compra', 'venta', 'renta'],
            ['hipoteca', 'financiamiento', 'crédito', 'banco'],
            ['ubicación', 'barrio', 'zona', 'colonia'],
            ['plusvalía', 'mercado', 'precio', 'valor']
        ];

        $selectedKeywords = [];
        foreach ($keywordGroups as $group) {
            $selectedKeywords[] = $this->faker->randomElement($group);
        }

        return implode(', ', $selectedKeywords);
    }
}
