<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coaches = Coach::all();
        return response()->json($coaches, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'peopleId' => 'required|uuid|unique:coach,peopleId',
            'role' => 'required|in:coach,official',
            'sportId' => 'required|uuid',
            'regionalRepresentative' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Generate UUID secara manual
        $validatedData = $validator->validated();
        $validatedData['id'] = Str::uuid(); // Tambahkan UUID ke data yang divalidasi

        // Create athlete
        $coach = Coach::create($validatedData);


        return response()->json($coach, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coach = Coach::find($id);

        if (is_null($coach)) {
            return response()->json(['message' => 'Coach not found'], 404);
        }

        return response()->json($coach, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coach = Coach::find($id);

        if (is_null($coach)) {
            return response()->json(['message' => 'Coach not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'peopleId' => 'uuid|unique:coach,peopleId,' . $id,
            'role' => 'in:coach,official',
            'sportId' => 'uuid',
            'regionalRepresentative' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $coach->update($request->all());

        return response()->json($coach, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coach = Coach::find($id);

        if (is_null($coach)) {
            return response()->json(['message' => 'Coach not found'], 404);
        }

        $coach->delete();

        return response()->json(['message' => 'Coach deleted successfully'], 204);
    }

    public function getpeople($id)
    {
        $athlete = Coach::where('peopleId', $id)->first();
        if (!$athlete) {
            return response()->json([
                'message' => 'Coach not found'
            ], 404);
        }

        return response()->json($athlete);
    }
}
