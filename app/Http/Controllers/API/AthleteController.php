<?php
// app/Http/Controllers/API/AthleteController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AthleteController extends Controller
{
    // Get all athletes
    public function index()
    {
        $athletes = Athlete::with(['person', 'sportClass', 'sport'])->get();
        return response()->json($athletes);
    }

    // Create a new athlete
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'peopleId'  => 'required|uuid|exists:people,id',
            'sportId' => 'required|uuid|exists:sports,id',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'achievements' => 'nullable|string',
            'regionalRepresentative' => 'required|string',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate UUID secara manual
        $validatedData = $validator->validated();
        $validatedData['id'] = Str::uuid(); // Tambahkan UUID ke data yang divalidasi

        // Create athlete
        $athlete = Athlete::create($validatedData);

        return response()->json($athlete, 201);
    }

    // Get a single athlete by ID
    public function show($id)
    {
        $athlete = Athlete::with(['person', 'sportClass'])->findOrFail($id);
        return response()->json($athlete);
    }


    // Update an athlete
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'peopleId' => 'required|uuid|exists:people,id',
            'sportClassId' => 'uuid|exists:sport_classes,id',
            'sportId' => 'required|uuid|exists:sports,id',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'achievements' => 'nullable|string',
            'regionalRepresentative' => 'required|string',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update athlete
        $athlete = Athlete::findOrFail($id);
        $athlete->update($validator->validated());

        return response()->json($athlete);
    }

    // Delete an athlete
    public function destroy($id)
    {
        $athlete = Athlete::findOrFail($id);
        $athlete->delete();

        return response()->json(null, 204);
    }

    public function getpeople($id) {
        $athlete = Athlete::where('peopleId', $id)->first();
        if (!$athlete) {
            return response()->json([
                'message' => 'Atleet not found'
            ], 404);
        }

        return response()->json($athlete);
    }

}
