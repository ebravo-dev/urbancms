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
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store property
        $property = new Property();
        $property->is_for_sale = $validated['is_for_sale'];
        $property->location_line1 = $validated['location_line1'] ?? null;
        $property->location_line2 = $validated['location_line2'] ?? null;
        $property->location_line3 = $validated['location_line3'] ?? null;
        $property->google_maps_url = $validated['google_maps_url'] ?? null;
        $property->feature1 = $validated['feature1'] ?? null;
        $property->feature2 = $validated['feature2'] ?? null;
        $property->feature3 = $validated['feature3'] ?? null;
        $property->feature4 = $validated['feature4'] ?? null;
        $property->feature5 = $validated['feature5'] ?? null;
        $property->feature6 = $validated['feature6'] ?? null;
        $property->feature7 = $validated['feature7'] ?? null;
        $property->feature8 = $validated['feature8'] ?? null;
        $property->investment = $validated['investment'] ?? null;

        // Store images if provided
        if ($request->hasFile('image1')) {
            $imagePath = $request->file('image1')->store('property-images', 'public');
            $property->image1 = $imagePath;
        }

        if ($request->hasFile('image2')) {
            $imagePath = $request->file('image2')->store('property-images', 'public');
            $property->image2 = $imagePath;
        }

        if ($request->hasFile('image3')) {
            $imagePath = $request->file('image3')->store('property-images', 'public');
            $property->image3 = $imagePath;
        }

        if ($request->hasFile('image4')) {
            $imagePath = $request->file('image4')->store('property-images', 'public');
            $property->image4 = $imagePath;
        }

        $property->save();

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
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_image1' => 'nullable|boolean',
            'delete_image2' => 'nullable|boolean',
            'delete_image3' => 'nullable|boolean',
            'delete_image4' => 'nullable|boolean',
        ]);

        // Update property
        $property->is_for_sale = $validated['is_for_sale'];
        $property->location_line1 = $validated['location_line1'] ?? null;
        $property->location_line2 = $validated['location_line2'] ?? null;
        $property->location_line3 = $validated['location_line3'] ?? null;
        $property->google_maps_url = $validated['google_maps_url'] ?? null;
        $property->feature1 = $validated['feature1'] ?? null;
        $property->feature2 = $validated['feature2'] ?? null;
        $property->feature3 = $validated['feature3'] ?? null;
        $property->feature4 = $validated['feature4'] ?? null;
        $property->feature5 = $validated['feature5'] ?? null;
        $property->feature6 = $validated['feature6'] ?? null;
        $property->feature7 = $validated['feature7'] ?? null;
        $property->feature8 = $validated['feature8'] ?? null;
        $property->investment = $validated['investment'] ?? null;

        // Handle image deletions
        if ($request->has('delete_image1') && $request->delete_image1) {
            if ($property->image1) {
                Storage::disk('public')->delete($property->image1);
                $property->image1 = null;
            }
        }

        if ($request->has('delete_image2') && $request->delete_image2) {
            if ($property->image2) {
                Storage::disk('public')->delete($property->image2);
                $property->image2 = null;
            }
        }

        if ($request->has('delete_image3') && $request->delete_image3) {
            if ($property->image3) {
                Storage::disk('public')->delete($property->image3);
                $property->image3 = null;
            }
        }

        if ($request->has('delete_image4') && $request->delete_image4) {
            if ($property->image4) {
                Storage::disk('public')->delete($property->image4);
                $property->image4 = null;
            }
        }

        // Update images if provided
        if ($request->hasFile('image1')) {
            if ($property->image1) {
                Storage::disk('public')->delete($property->image1);
            }
            $imagePath = $request->file('image1')->store('property-images', 'public');
            $property->image1 = $imagePath;
        }

        if ($request->hasFile('image2')) {
            if ($property->image2) {
                Storage::disk('public')->delete($property->image2);
            }
            $imagePath = $request->file('image2')->store('property-images', 'public');
            $property->image2 = $imagePath;
        }

        if ($request->hasFile('image3')) {
            if ($property->image3) {
                Storage::disk('public')->delete($property->image3);
            }
            $imagePath = $request->file('image3')->store('property-images', 'public');
            $property->image3 = $imagePath;
        }

        if ($request->hasFile('image4')) {
            if ($property->image4) {
                Storage::disk('public')->delete($property->image4);
            }
            $imagePath = $request->file('image4')->store('property-images', 'public');
            $property->image4 = $imagePath;
        }

        $property->save();

        return redirect()->route('properties.index')->with('success', 'Propiedad actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Delete all images
        if ($property->image1) {
            Storage::disk('public')->delete($property->image1);
        }
        if ($property->image2) {
            Storage::disk('public')->delete($property->image2);
        }
        if ($property->image3) {
            Storage::disk('public')->delete($property->image3);
        }
        if ($property->image4) {
            Storage::disk('public')->delete($property->image4);
        }

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Propiedad eliminada exitosamente');
    }
}
