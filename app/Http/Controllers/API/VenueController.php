<?php
// app/Http/Controllers/API/VenueController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $validatedData = $validator->validated();

        // Ambil 8 desimal pertama dari latitude dan longitude
        $latitude = number_format($validatedData['latitude'], 8, '.', '');
        $longitude = number_format($validatedData['longitude'], 8, '.', '');

        // Cek apakah latitude dan longitude sudah terdaftar dengan presisi 8 desimal
        $existingVenue = Venue::whereRaw("ROUND(latitude, 8) = ?", [$latitude])
        ->whereRaw("ROUND(longitude, 8) = ?", [$longitude])
        ->first();

        if ($existingVenue) {
            return response()->json([
                'message' => "Lokasi ini sudah didaftarkan dengan nama {$existingVenue->name}"
            ], 422);
        }

        $validatedData['id'] = Str::uuid(); // Tambahkan UUID ke data yang divalidasi
        $validatedData['latitude'] = $latitude;
        $validatedData['longitude'] = $longitude;

        $venue = Venue::create($validatedData);

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

    public function getVenue(Request $request)
    {
        $query = Venue::orderBy('created_at', 'desc');

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Hitung total data sebelum filtering
        $totalData = Venue::count();
        $totalFiltered = $query->count();

        // Ambil data sesuai pagination DataTables
        $venues = $query->offset($request->start)
        ->limit($request->length)
        ->get();

        // Jika tidak ada data
        if ($venues->isEmpty()) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Mapping data untuk response DataTables
        $filteredData = $venues->map(function ($venue) {
            return [
                'id' => $venue->id,
                'name' => ucwords(strtolower($venue->name)),
                'location' => ucwords(strtolower($venue->location)),
                'latitude' => $venue->latitude,
                'longitude' => $venue->longitude,
                'status' => $venue->status,
                'updated_timestamp' => Carbon::parse($venue->updated_at)->translatedFormat('d F Y'),
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $filteredData->values()
        ]);
    }
}
