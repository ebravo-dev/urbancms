<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with('images')->latest()->paginate(10);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datasheet' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store property
        $property = new Property();
        $property->title = $validated['title'];
        $property->description = $validated['description'];

        // Store datasheet if provided
        if ($request->hasFile('datasheet')) {
            $datasheetPath = $request->file('datasheet')->store('datasheets', 'public');
            $property->datasheet_path = $datasheetPath;
        }

        $property->save();

        // Store images if provided
        if ($request->hasFile('images')) {
            $order = 0;
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('property-images', 'public');

                $propertyImage = new PropertyImage();
                $propertyImage->property_id = $property->id;
                $propertyImage->image_path = $imagePath;
                $propertyImage->order = $order++;
                $propertyImage->save();
            }
        }

        return redirect()->route('properties.index')->with('success', 'Propiedad creada exitosamente');
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datasheet' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images.*' => 'nullable|integer',
        ]);

        // Update property
        $property->title = $validated['title'];
        $property->description = $validated['description'];

        // Update datasheet if provided
        if ($request->hasFile('datasheet')) {
            // Delete old datasheet if exists
            if ($property->datasheet_path) {
                Storage::disk('public')->delete($property->datasheet_path);
            }

            $datasheetPath = $request->file('datasheet')->store('datasheets', 'public');
            $property->datasheet_path = $datasheetPath;
        }

        $property->save();

        // Delete images if requested
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = PropertyImage::find($imageId);
                if ($image && $image->property_id == $property->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Add new images if provided
        if ($request->hasFile('new_images')) {
            $maxOrder = $property->images()->max('order') ?? -1;
            $order = $maxOrder + 1;

            foreach ($request->file('new_images') as $image) {
                $imagePath = $image->store('property-images', 'public');

                $propertyImage = new PropertyImage();
                $propertyImage->property_id = $property->id;
                $propertyImage->image_path = $imagePath;
                $propertyImage->order = $order++;
                $propertyImage->save();
            }
        }

        return redirect()->route('properties.index')->with('success', 'Propiedad actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Delete all images
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete datasheet if exists
        if ($property->datasheet_path) {
            Storage::disk('public')->delete($property->datasheet_path);
        }

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Propiedad eliminada exitosamente');
    }

    /**
     * Reorder images
     */
    public function reorderImages(Request $request, Property $property)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|integer|exists:property_images,id',
        ]);

        foreach ($validated['images'] as $order => $imageId) {
            $image = PropertyImage::find($imageId);
            if ($image && $image->property_id == $property->id) {
                $image->order = $order;
                $image->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
