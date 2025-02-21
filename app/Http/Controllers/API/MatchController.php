<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Matches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    public function index()
    {
        $matches = Matches::all();
        return response()->json($matches);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'sport_id' => 'required|uuid|exists:sports,id',
            'venue_id' => 'required|uuid|exists:venues,id',
            'sport_class_id' => 'required|uuid|exists:sport_classes,id|unique:matches,sport_class_id',
            'schedule_id' => 'nullable|uuid|exists:schedules,id',
            'status' => 'required|in:0,1',
        ]);

        // Jika validasi gagal, kembalikan response error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Buat match baru
        $match = Matches::create($request->all());
        return response()->json($match, 201);
    }

    public function show($id)
    {
        $match = Matches::findOrFail($id);
        return response()->json($match);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'sport_id' => 'sometimes|uuid|exists:sports,id',
            'venue_id' => 'sometimes|uuid|exists:venues,id',
            'sport_class_id' => 'sometimes|uuid|exists:sport_classes,id|unique:matches,sport_class_id,' . $id,
            'schedule_id' => 'sometimes|uuid|exists:schedules,id',
            'status' => 'sometimes|in:0,1',
        ]);

        // Jika validasi gagal, kembalikan response error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update match
        $match = Matches::findOrFail($id);
        $match->update($request->all());
        return response()->json($match);
    }

    public function destroy($id)
    {
        Matches::destroy($id);
        return response()->json(null, 204);
    }
}
