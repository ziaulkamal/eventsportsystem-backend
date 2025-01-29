<?php
// app/Http/Controllers/API/SportClassController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SportClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SportClassController extends Controller
{
    // Get all sport classes
    public function index()
    {
        $sportClasses = SportClass::all();
        return response()->json($sportClasses);
    }

    // Create a new sport class
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'sportId' => 'required|uuid',
            'type' => 'required|in:male,female',
            'classOption' => 'required|string',
            'imageId' => 'nullable|uuid',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create sport class
        $sportClass = SportClass::create($validator->validated());

        return response()->json($sportClass, 201);
    }

    // Get a single sport class by ID
    public function show($id)
    {
        $sportClass = SportClass::findOrFail($id);
        return response()->json($sportClass);
    }

    // Update a sport class
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'sportId' => 'sometimes|uuid',
            'type' => 'sometimes|in:male,female',
            'classOption' => 'sometimes|string',
            'imageId' => 'nullable|uuid',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update sport class
        $sportClass = SportClass::findOrFail($id);
        $sportClass->update($validator->validated());

        return response()->json($sportClass);
    }

    // Delete a sport class
    public function destroy($id)
    {
        $sportClass = SportClass::findOrFail($id);
        $sportClass->delete();

        return response()->json(null, 204);
    }
}
