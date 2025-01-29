<?php
// app/Http/Controllers/API/SportController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SportController extends Controller
{
    // Get all sports
    public function index()
    {
        $sports = Sport::all();
        return response()->json($sports);
    }

    // Create a new sport
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'imageId' => 'nullable|uuid',
            'status' => 'sometimes|boolean',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create sport
        $sport = Sport::create($validator->validated());

        return response()->json($sport, 201);
    }

    // Get a single sport by ID
    public function show($id)
    {
        $sport = Sport::findOrFail($id);
        return response()->json($sport);
    }

    // Update a sport
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'imageId' => 'nullable|uuid',
            'status' => 'sometimes|boolean',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update sport
        $sport = Sport::findOrFail($id);
        $sport->update($validator->validated());

        return response()->json($sport);
    }

    // Delete a sport
    public function destroy($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->delete();

        return response()->json(null, 204);
    }
}
