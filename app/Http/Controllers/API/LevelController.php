<?php
// app/Http/Controllers/API/LevelController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    // Get all levels
    public function index()
    {
        $levels = Level::all();
        return response()->json($levels);
    }

    // Create a new level
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'role' => 'required|integer|unique:levels,role',
            'name' => 'required|string',
            'access' => 'required|json',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create level
        $level = Level::create($validator->validated());

        return response()->json($level, 201);
    }

    // Get a single level by ID
    public function show($id)
    {
        $level = Level::findOrFail($id);
        return response()->json($level);
    }

    // Update a level
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'role' => 'sometimes|integer|unique:levels,role,' . $id,
            'name' => 'sometimes|string',
            'access' => 'sometimes|json',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update level
        $level = Level::findOrFail($id);
        $level->update($validator->validated());

        return response()->json($level);
    }

    // Delete a level
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        return response()->json(null, 204);
    }
}
