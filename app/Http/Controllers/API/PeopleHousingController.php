<?php
// app/Http/Controllers/API/PeopleHousingController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PeopleHousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeopleHousingController extends Controller
{
    // Get all people housing records
    public function index()
    {
        $peopleHousing = PeopleHousing::with(['person', 'house', 'responsible'])->get();
        return response()->json($peopleHousing);
    }

    // Create a new people housing record
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'peopleId' => 'required|uuid|exists:people,id',
            'houseId' => 'required|uuid|exists:housing,id',
            'responsibleId' => 'required|uuid|exists:people,id',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create people housing record
        $peopleHousing = PeopleHousing::create($validator->validated());

        return response()->json($peopleHousing, 201);
    }

    // Get a single people housing record by ID
    public function show($id)
    {
        $peopleHousing = PeopleHousing::with(['person', 'house', 'responsible'])->findOrFail($id);
        return response()->json($peopleHousing);
    }

    // Update a people housing record
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'peopleId' => 'sometimes|uuid|exists:people,id',
            'houseId' => 'sometimes|uuid|exists:housing,id',
            'responsibleId' => 'sometimes|uuid|exists:people,id',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update people housing record
        $peopleHousing = PeopleHousing::findOrFail($id);
        $peopleHousing->update($validator->validated());

        return response()->json($peopleHousing);
    }

    // Delete a people housing record
    public function destroy($id)
    {
        $peopleHousing = PeopleHousing::findOrFail($id);
        $peopleHousing->delete();

        return response()->json(null, 204);
    }
}
