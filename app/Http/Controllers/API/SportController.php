<?php
// app/Http/Controllers/API/SportController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SportController extends Controller
{
    // Get all sports
    public function index()
    {
        $sports = Sport::orderBy('name', 'asc')->get();
        return response()->json($sports);
    }

    // Create a new sport
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sports,name', // Perbaikan di sini
            'description' => 'nullable|string',
            'imageId' => 'nullable|uuid',
            'status' => 'sometimes|boolean',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validatedData = $validator->validated();
            $validatedData['id'] = Str::uuid();
            $validatedData['description'] = "-";

            // Create sport
            $sport = Sport::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Olahraga berhasil ditambahkan!',
                'data' => $sport
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data!',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function searchSport(Request $request)
    {
        $term = $request->input('term'); // Mendapatkan kata kunci pencarian

        // Mengambil data dari model dan melakukan pencarian dengan 'like'
        $cabor = Sport::where('name', 'LIKE', "%{$term}%")
        ->orderBy('name') // Sortir berdasarkan nama secara alfabetis
        ->get();

        // Mengembalikan data dalam format yang sesuai dengan Select2
        return response()->json([
            'results' => $cabor->map(function ($item) {
                return [
                    'id' => $item->id, // id cabang olahraga
                    'text' => $item->name // nama cabang olahraga
                ];
            })
        ]);
    }

    public function getSport(Request $request)
    {
        $query = Sport::orderBy('created_at', 'desc');

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Hitung total data sebelum filtering
        $totalData = Sport::count();

        // Hitung total data setelah filtering
        $totalFiltered = $query->count();

        // Ambil data sesuai pagination DataTables
        $sport = $query->offset($request->start)
            ->limit($request->length)
            ->get();

        // Mapping data untuk DataTables
        $filteredData = $sport->map(function ($sports) {
            return [
                'id' => $sports->id,
                'name' => $sports->name, // Nama cabang olahraga
                'status' => $sports->status,
                'updated_timestamp' => $sports->updated_at,
            ];
        });

        // Return response sesuai format DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $filteredData
        ]);
    }
}
