<?php
// app/Http/Controllers/API/VenueController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    // Get all venues
    public function index()
    {
        $venues = Venue::all();
        return response()->json($venues);
    }

    // Create a new venue
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'sometimes|in:active,inactive',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create venue
        $venue = Venue::create($validator->validated());

        return response()->json($venue, 201);
    }

    // Get a single venue by ID
    public function show($id)
    {
        $venue = Venue::findOrFail($id);
        return response()->json($venue);
    }

    // Update a venue
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'status' => 'sometimes|in:active,inactive',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update venue
        $venue = Venue::findOrFail($id);
        $venue->update($validator->validated());

        return response()->json($venue);
    }

    // Delete a venue
    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return response()->json(null, 204);
    }
}
