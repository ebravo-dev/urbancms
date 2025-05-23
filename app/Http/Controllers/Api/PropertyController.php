<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyCollection;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a paginated listing of properties.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\PropertyCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        // Build the query with filters
        $query = Property::query();

        // Filter by sale/rent type
        if ($request->has('is_for_sale')) {
            $query->where('is_for_sale', $request->boolean('is_for_sale'));
        }

        // Filter by investment amount range
        if ($request->has('min_investment')) {
            $query->where('investment', '>=', $request->input('min_investment'));
        }

        if ($request->has('max_investment')) {
            $query->where('investment', '<=', $request->input('max_investment'));
        }

        // Filter by location
        if ($request->has('location')) {
            $location = $request->input('location');
            $query->where(function ($q) use ($location) {
                $q->where('location_line1', 'LIKE', "%{$location}%")
                    ->orWhere('location_line2', 'LIKE', "%{$location}%")
                    ->orWhere('location_line3', 'LIKE', "%{$location}%");
            });
        }

        // Filter by features
        if ($request->has('feature')) {
            $feature = $request->input('feature');
            $query->where(function ($q) use ($feature) {
                $q->where('feature1', 'LIKE', "%{$feature}%")
                    ->orWhere('feature2', 'LIKE', "%{$feature}%")
                    ->orWhere('feature3', 'LIKE', "%{$feature}%")
                    ->orWhere('feature4', 'LIKE', "%{$feature}%")
                    ->orWhere('feature5', 'LIKE', "%{$feature}%")
                    ->orWhere('feature6', 'LIKE', "%{$feature}%")
                    ->orWhere('feature7', 'LIKE', "%{$feature}%")
                    ->orWhere('feature8', 'LIKE', "%{$feature}%");
            });
        }

        // Sort by created date (default), investment amount, etc.
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validate sort parameters to prevent SQL injection
        $allowedSortFields = ['created_at', 'investment', 'is_for_sale'];
        $allowedSortDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortFields) && in_array($sortDirection, $allowedSortDirections)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->latest(); // Default sorting
        }

        $properties = $query->paginate($perPage);

        return new PropertyCollection($properties);
    }

    /**
     * Display the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \App\Http\Resources\PropertyResource
     */
    public function show(Property $property)
    {
        return new PropertyResource($property);
    }
}
