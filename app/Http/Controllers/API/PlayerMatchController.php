<?php
// app/Http/Controllers/API/PlayerMatchController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PlayerMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerMatchController extends Controller
{
    // Get all player matches
    public function index()
    {
        $playerMatches = PlayerMatch::with(['athlete', 'schedule'])->get();
        return response()->json($playerMatches);
    }

    // Create a new player match
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'athleteId' => 'required|uuid|exists:athletes,id',
            'scheduleId' => 'required|uuid|exists:schedules,id',
            'grade' => 'nullable|numeric|between:0,100',
            'score' => 'nullable|numeric|between:0,100',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create player match
        $playerMatch = PlayerMatch::create($validator->validated());

        return response()->json($playerMatch, 201);
    }

    // Get a single player match by ID
    public function show($id)
    {
        $playerMatch = PlayerMatch::with(['athlete', 'schedule'])->findOrFail($id);
        return response()->json($playerMatch);
    }

    // Update a player match
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'athleteId' => 'sometimes|uuid|exists:athletes,id',
            'scheduleId' => 'sometimes|uuid|exists:schedules,id',
            'grade' => 'nullable|numeric|between:0,100',
            'score' => 'nullable|numeric|between:0,100',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update player match
        $playerMatch = PlayerMatch::findOrFail($id);
        $playerMatch->update($validator->validated());

        return response()->json($playerMatch);
    }

    // Delete a player match
    public function destroy($id)
    {
        $playerMatch = PlayerMatch::findOrFail($id);
        $playerMatch->delete();

        return response()->json(null, 204);
    }
}
