<?php
// app/Http/Controllers/API/SportClassController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SportClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        // dd($request->all());
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

        $validatedData = $validator->validated();
        $validatedData['id'] = Str::uuid();
        // Create sport class
        $sportClass = SportClass::create($validatedData);

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

    public function getSportById($id) {
        $sportClass = SportClass::with('sports')->where('sportId', $id)->get();
        return response()->json($sportClass);
    }

    public function getSportClass(Request $request)
    {
        $query = SportClass::with('sports')->orderBy('created_at', 'desc');

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('classOption', 'LIKE', "%{$search}%");
            });
        }

        // Hitung total data sebelum filtering
        $totalData = SportClass::count();

        // Hitung total data setelah filtering
        $totalFiltered = $query->count();

        // Ambil data sesuai pagination DataTables
        $sportClasses = $query->offset($request->start)
            ->limit($request->length)
            ->get();

        // Mapping data untuk DataTables
        $filteredData = $sportClasses->map(function ($sportClass) {
            return [
                'id' => $sportClass->id,
                'sport' => optional($sportClass->sports)->name, // Nama cabang olahraga
                'type' => $sportClass->type,
                'classOption' => $sportClass->classOption,
                'updated_timestamp' => $sportClass->updated_at->format('Y-m-d H:i:s'), // Format tanggal
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
