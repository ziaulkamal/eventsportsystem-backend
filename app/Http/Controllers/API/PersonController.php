<?php
// app/Http/Controllers/API/PersonController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    // Get all people
    public function index()
    {
        $people = Person::all();
        return response()->json($people);
    }

    // Create a new person
    public function store(Request $request)
    {
        // Aturan validasi
        $rules = [
            'fullName' => 'required|string|max:255',
            'age' => 'required|integer',
            'birthdate' => 'required|date',
            'identityNumber' => 'required|string|unique:people',
            'familyIdentityNumber' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'streetAddress' => 'required|string',
            'religion' => 'required|integer',
            'provinceId' => 'required|integer',
            'regencieId' => 'required|integer',
            'districtId' => 'required|integer',
            'villageId' => 'required|integer',
            'phoneNumber' => 'required|string',
            'email' => 'nullable|email',
            'documentId' => 'required|uuid', // documentId tetap dari frontend
            'userId' => 'nullable|uuid',
        ];

        // Validasi data
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembalikan error
        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            $errors = $validator->errors();

            // Identifikasi error untuk identityNumber
            if ($errors->has('identityNumber')) {
                return response()->json([
                    'error_code' => 'IDENTITY_NUMBER_TAKEN', // Kode error khusus
                    'message' => 'Nomor KTP sudah terdaftar.', // Pesan error dalam bahasa Indonesia
                ], 422); // Status code 422 (Unprocessable Content)
            }

            // Jika ada error lainnya, kembalikan semua error
            return response()->json([
                'errors' => $errors,
            ], 422);
        }

        // Jika validasi berhasil, simpan data ke database
        Document::insert([
            'id' => $request->documentId,
        ]);
        // Generate UUID untuk id
        $id = Str::uuid();

        // Simpan data ke tabel people
        $person = Person::create([
            'id' => $id, // id dibuat otomatis
            ...$validator->validated(), // Spread operator untuk memasukkan semua data yang sudah divalidasi
        ]);
        // Kembalikan respons sukses
        return response()->json($person, 201); // Status code 201 (Created)
    }

    // Get a single person by ID
    public function show($id)
    {
        $person = Person::findOrFail($id);
        return response()->json($person);
    }

    // Update a person
    public function update(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'fullName' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer',
            'birthdate' => 'sometimes|date',
            'identityNumber' => 'sometimes|string|unique:people,identityNumber,' . $id,
            'familyIdentityNumber' => 'sometimes|string',
            'gender' => 'sometimes|in:male,female',
            'streetAddress' => 'sometimes|string',
            'religion' => 'sometimes|integer',
            'provinceId' => 'sometimes|integer',
            'regencieId' => 'sometimes|integer',
            'districtId' => 'sometimes|integer',
            'villageId' => 'sometimes|integer',
            'phoneNumber' => 'sometimes|string',
            'email' => 'nullable|email',
            'documentId' => 'sometimes|uuid',
            'userId' => 'nullable|uuid',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update person
        $person = Person::findOrFail($id);
        $person->update($validator->validated());

        return response()->json($person);
    }

    // Delete a person
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json(null, 204);
    }

    public function findByNIK($nik)
    {
        $person = Person::where('identityNumber', $nik)->first();

        if (!$person) {
            return response()->json(200);
        }

        return response()->json($person, 201);
    }

    public function athlete()
    {
        // Ambil semua data Person beserta relasinya (Athlete, Document, dan regional_representative)
        $people = Person::with([
            'province',
            'regencie',
            'district',
            'village',
            'athlete.sport',
            'athlete.sportClass',
            'athlete.regional',
            'document'
        ])
        ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at descending
        ->get();

        // Jika tidak ada data Person
        if ($people->isEmpty()) {
            return response()->json([
                'message' => 'No person data found',
            ], 404);
        }

        // Daftar agama dalam bentuk array
        $religionOptions = [
            1 => 'Islam',
            2 => 'Kristen Katolik',
            3 => 'Kristen Protestan',
            4 => 'Hindu',
            5 => 'Buddha',
            6 => 'Konghucu',
        ];

        // Filter data untuk hanya memasukkan data dengan relasi athlete
        $filteredData = $people
        ->filter(function ($person) {
            return $person->athlete !== null; // Hanya masukkan data jika athlete tidak null
        })
            ->map(function ($person) use ($religionOptions) {
                $athlete = $person->athlete;
                $height = $athlete->height ? intval($athlete->height) . ' Cm' : 'Tidak ada data';
                $weight = $athlete->weight ? intval($athlete->weight) . ' Kg' : 'Tidak ada data';

                return [
                    'id'    => $person->id,
                    'identityNumber' => $person->identityNumber,
                    'familyIdentityNumber' => $person->familyIdentityNumber,
                    'phoneNumber'   => $person->phoneNumber,
                    'fullName' => ucwords(strtolower($person->fullName)),
                    'birthdate' => $person->birthdate,
                    'age' => $person->age . ' Tahun',
                    'religion' => $religionOptions[$person->religion] ?? 'Tidak ada data', // Konversi agama ke string
                    'address' => ucwords(strtolower($person->streetAddress)),
                    'province' => ucwords(strtolower(optional($person->province)->name)),
                    'regencie' => ucwords(strtolower(optional($person->regencie)->name)),
                    'district' => ucwords(strtolower(optional($person->district)->name)),
                    'village' => ucwords(strtolower(optional($person->village)->name)),
                    'imageProfile' => optional($person->document)->imageProfile,
                    'regional_representative' => optional($athlete->regional)->name,
                    'sport' => optional($athlete->sport)->name,
                    'gender' => $person->gender,
                    'weight' => $weight,
                    'height' => $height,
                    'updated_timestamp' => $person->updated_at,
                    'document' => $person->document
                ];
            });

        // Return response
        return response()->json($filteredData->values(), 200); // Gunakan values() untuk reset key array

        // Return response
        return response()->json($filteredData, 200);
    }
}
