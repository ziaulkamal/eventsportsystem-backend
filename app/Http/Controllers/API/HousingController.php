<?php
// app/Http/Controllers/API/HousingController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Housing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HousingController extends Controller
{
    // Get all housing
    public function index()
    {
        $housing = Housing::all();
        return response()->json($housing);
    }

    // Create a new housing
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'phoneNumber' => 'required|string',
            'capacity' => 'nullable|integer',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $validatedData = $validator->validated();
        $validatedData['id'] = Str::uuid();
        // Create housing
        $housing = Housing::create($validatedData);

        return response()->json($housing, 201);
    }

    // Get a single housing by ID
    public function show($id)
    {
        $housing = Housing::findOrFail($id);
        return response()->json($housing);
    }

    // Update a housing
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'phoneNumber' => 'sometimes|string',
            'capacity' => 'nullable|integer',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update housing
        $housing = Housing::findOrFail($id);
        $housing->update($validator->validated());

        return response()->json($housing);
    }

    // Delete a housing
    public function destroy($id)
    {
        $housing = Housing::findOrFail($id);
        $housing->delete();

        return response()->json(null, 204);
    }
}
