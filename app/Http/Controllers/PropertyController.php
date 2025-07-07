<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Traits\HandlesImageProcessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    use HandlesImageProcessing;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::latest()->paginate(10);
        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_for_sale' => 'required|boolean',
            'location_line1' => 'nullable|string|max:255',
            'location_line2' => 'nullable|string|max:255',
            'location_line3' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url|max:2000',
            'feature1' => 'nullable|string|max:255',
            'feature2' => 'nullable|string|max:255',
            'feature3' => 'nullable|string|max:255',
            'feature4' => 'nullable|string|max:255',
            'feature5' => 'nullable|string|max:255',
            'feature6' => 'nullable|string|max:255',
            'feature7' => 'nullable|string|max:255',
            'feature8' => 'nullable|string|max:255',
            'investment' => 'nullable|numeric',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Crear la propiedad con datos básicos
        $propertyData = collect($validated)->except(['image1', 'image2', 'image3', 'image4'])->toArray();
        
        // Procesar y convertir imágenes a WebP
        $imageData = $this->processImages(
            ['image1', 'image2', 'image3', 'image4'],
            $request,
            null,
            'property-images',
            [
                'max_width' => config('image.property_max_width', 1200),
                'max_height' => config('image.property_max_height', 800),
                'quality' => config('image.quality', 85),
                'context' => [
                    'type' => 'property',
                    'is_for_sale' => $validated['is_for_sale'] ?? null,
                    'location_line1' => $validated['location_line1'] ?? null,
                    'location_line2' => $validated['location_line2'] ?? null,
                    'location_line3' => $validated['location_line3'] ?? null,
                    'feature1' => $validated['feature1'] ?? null,
                    'feature2' => $validated['feature2'] ?? null,
                    'feature3' => $validated['feature3'] ?? null,
                    'feature4' => $validated['feature4'] ?? null,
                    'feature5' => $validated['feature5'] ?? null,
                    'feature6' => $validated['feature6'] ?? null,
                    'feature7' => $validated['feature7'] ?? null,
                    'feature8' => $validated['feature8'] ?? null,
                ]
            ]
        );

        // Merge de datos de propiedad e imágenes
        $propertyData = array_merge($propertyData, $imageData);

        // Crear propiedad
        $property = Property::create($propertyData);

        return redirect()->route('properties.index')->with('success', 'Propiedad creada exitosamente. Las imágenes han sido optimizadas automáticamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'is_for_sale' => 'required|boolean',
            'location_line1' => 'nullable|string|max:255',
            'location_line2' => 'nullable|string|max:255',
            'location_line3' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url|max:2000',
            'feature1' => 'nullable|string|max:255',
            'feature2' => 'nullable|string|max:255',
            'feature3' => 'nullable|string|max:255',
            'feature4' => 'nullable|string|max:255',
            'feature5' => 'nullable|string|max:255',
            'feature6' => 'nullable|string|max:255',
            'feature7' => 'nullable|string|max:255',
            'feature8' => 'nullable|string|max:255',
            'investment' => 'nullable|numeric',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Actualizar datos básicos de la propiedad
        $propertyData = collect($validated)->except(['image1', 'image2', 'image3', 'image4'])->toArray();
        
        // Procesar y convertir imágenes a WebP (reemplazando las existentes)
        $imageData = $this->processImages(
            ['image1', 'image2', 'image3', 'image4'],
            $request,
            $property,
            'property-images',
            [
                'max_width' => config('image.property_max_width', 1200),
                'max_height' => config('image.property_max_height', 800),
                'quality' => config('image.quality', 85),
                'context' => [
                    'type' => 'property',
                    'is_for_sale' => $validated['is_for_sale'] ?? $property->is_for_sale,
                    'location_line1' => $validated['location_line1'] ?? $property->location_line1,
                    'location_line2' => $validated['location_line2'] ?? $property->location_line2,
                    'location_line3' => $validated['location_line3'] ?? $property->location_line3,
                    'feature1' => $validated['feature1'] ?? $property->feature1,
                    'feature2' => $validated['feature2'] ?? $property->feature2,
                    'feature3' => $validated['feature3'] ?? $property->feature3,
                    'feature4' => $validated['feature4'] ?? $property->feature4,
                    'feature5' => $validated['feature5'] ?? $property->feature5,
                    'feature6' => $validated['feature6'] ?? $property->feature6,
                    'feature7' => $validated['feature7'] ?? $property->feature7,
                    'feature8' => $validated['feature8'] ?? $property->feature8,
                ]
            ]
        );

        // Merge de datos de propiedad e imágenes
        $propertyData = array_merge($propertyData, $imageData);

        // Actualizar propiedad
        $property->update($propertyData);

        return redirect()->route('properties.index')->with('success', 'Propiedad actualizada exitosamente. Las imágenes han sido optimizadas automáticamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Delete all images using the trait method
        $this->deleteOldImage($property->image1);
        $this->deleteOldImage($property->image2);
        $this->deleteOldImage($property->image3);
        $this->deleteOldImage($property->image4);

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Propiedad eliminada exitosamente');
    }
}
