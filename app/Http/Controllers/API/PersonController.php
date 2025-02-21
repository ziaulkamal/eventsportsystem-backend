<?php
// app/Http/Controllers/API/PersonController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Person;
use Carbon\Carbon;
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
            'birthdate' => 'required',
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
            // 'documentId' => 'required|uuid', // documentId tetap dari frontend
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
        $uuidDocument = Str::uuid();
        // Jika validasi berhasil, simpan data ke database
        Document::insert([
            'id' => $uuidDocument,
        ]);
        // Format ulang birthdate menjadi timestamp ISO 8601
        $formattedBirthdate = Carbon::parse($request->birthdate)->toIso8601String(); // Format ISO 8601
        // dd($formattedBirthdate);
        // Simpan data ke tabel people
        $person = Person::create([
            'id' => Str::uuid(), // Generate UUID untuk id
            'fullName' => $request->fullName,
            'age' => $this->calculateAge($request->birthdate),
            'birthdate' => $formattedBirthdate, // Menyimpan birthdate dengan format timestamp ISO 8601
            'identityNumber' => $request->identityNumber,
            'familyIdentityNumber' => $request->familyIdentityNumber,
            'gender' => $request->gender,
            'streetAddress' => $request->streetAddress,
            'religion' => $request->religion,
            'provinceId' => $request->provinceId,
            'regencieId' => $request->regencieId,
            'districtId' => $request->districtId,
            'villageId' => $request->villageId,
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'documentId' => $uuidDocument,
            'userId' => $request->userId,
        ]);

        // Kembalikan respons sukses
        return response()->json($person, 201);// Status code 201 (Created)
    }

    // Get a single person by ID
    public function show($id)
    {
        $person = Person::findOrFail($id);

        return response()->json([
            'id' => $person->id,
            'fullName' => $person->fullName,
            'age' => $person->age,
            'birthdate' => $person->birthdate ? Carbon::parse($person->birthdate)->format('d-m-Y') : null,
            'identityNumber' => $person->identityNumber,
            'familyIdentityNumber' => $person->familyIdentityNumber,
            'gender' => $person->gender,
            'streetAddress' => $person->streetAddress,
            'religion' => $person->religion,
            'provinceId' => $person->provinceId,
            'regencieId' => $person->regencieId,
            'districtId' => $person->districtId,
            'villageId' => $person->villageId,
            'phoneNumber' => $person->phoneNumber,
            'email' => $person->email,
            'documentId' => $person->documentId,
            'userId' => $person->userId,
            'created_at' => $person->created_at,
            'updated_at' => $person->updated_at
        ]);
    }

    // Update a person
    public function update(Request $request, $id)
    {
        // dd($request, $id);
        // Validation rules
        $validator = Validator::make($request->all(), [
            'fullName' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer',
            'birthdate' => 'sometimes|date',
            'identityNumber' => 'sometimes|string|unique:people,identityNumber,' . $id,
            'familyIdentityNumber' => 'nullable|string',
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
        if ($request->has('documentId')) {
            $document = Document::find($request->documentId);
            if (!$document) {
                return response()->json(['error' => 'Document not found'], 404);
            }
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

        // dd($people);
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
                // dd($athlete->id);
                $height = $athlete->height ? intval($athlete->height) . ' Cm' : 'Tidak ada data';
                $weight = $athlete->weight ? intval($athlete->weight) . ' Kg' : 'Tidak ada data';

                return [
                    'id'                    => $person->id,
                    'identityNumber'        => $person->identityNumber,
                    'familyIdentityNumber'  => $person->familyIdentityNumber,
                    'phoneNumber'           => $person->phoneNumber,
                    'fullName'              => ucwords(strtolower($person->fullName)),
                    'birthdate'             => $person->birthdate,
                    'age'                   => $person->age . ' Tahun',
                    'religion'              => $religionOptions[$person->religion] ?? 'Tidak ada data', // Konversi agama ke string
                    'address'               => ucwords(strtolower($person->streetAddress)),
                    'province'              => ucwords(strtolower(optional($person->province)->name)),
                    'regencie'              => ucwords(strtolower(optional($person->regencie)->name)),
                    'district'              => ucwords(strtolower(optional($person->district)->name)),
                    'village'               => ucwords(strtolower(optional($person->village)->name)),
                    'imageProfile'          => optional($person->document)->docsImageProfile,
                    'regional_representative' => optional($athlete->regional)->name,
                    'sport'                 => optional($athlete->sport)->name,
                    'gender'                => $person->gender,
                    'weight'                => $weight,
                    'height'                => $height,
                    'updated_timestamp'     => Carbon::parse($person->updated_at)->translatedFormat('d F Y'),
                    'documentId'            => $person->documentId,
                    'athleetId'             => $athlete->id,
                ];
            });

        // Return response
        return response()->json($filteredData->values(), 200); // Gunakan values() untuk reset key array

        // Return response
        return response()->json($filteredData, 200);
    }

    public function coach()
    {
        // Ambil semua data Person beserta relasinya (Athlete, Document, dan regional_representative)
        $people = Person::with([
            'province',
            'regencie',
            'district',
            'village',
            'coach.sport',
            'coach.sportClass',
            'coach.regional',
            'document'
        ])
        ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at descending
        ->get();

        // dd($people);
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

        // Filter data untuk hanya memasukkan data dengan relasi coach
        $filteredData = $people
        ->filter(function ($person) {
            return $person->coach !== null; // Hanya masukkan data jika coach tidak null
        })
            ->map(function ($person) use ($religionOptions) {
                $coach = $person->coach;

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
                    'imageProfile' => optional($person->document)->docsImageProfile,
                    'regional_representative' => optional($coach->regional)->name,
                    'sport' => optional($coach->sport)->name,
                    'gender' => $person->gender,
                    'updated_timestamp' => $person->updated_at,
                    'documentId' => $person->documentId,
                    'athleetId' => $coach->id,
                ];
            });

        // Return response
        return response()->json($filteredData->values(), 200); // Gunakan values() untuk reset key array

        // Return response
        return response()->json($filteredData, 200);
    }

    private function calculateAge($value)
    {
        // Mengonversi tanggal lahir ke objek Carbon
        $birthdate = Carbon::parse($value);

        // Mendapatkan tanggal saat ini
        $now = Carbon::now();

        // Menghitung usia berdasarkan tahun
        $age = $now->diffInYears($birthdate);

        // Memeriksa apakah bulan dan hari sekarang sudah melewati tanggal lahir
        if ($now->isBefore($birthdate->copy()->addYears($age))) {
            $age--; // Jika belum, usia tidak genap
        }

        return $age;
    }

    public function getCoaches(Request $request)
    {
        $query = Person::with([
            'province',
            'regencie',
            'district',
            'village',
            'coach.sport',
            'coach.sportClass',
            'coach.regional',
            'document'
        ])->whereHas('coach', function ($query) {
            $query->whereIn('role', ['coach', 'coach_asisten']);
        })
        ->orderBy('created_at', 'desc');

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('fullName', 'LIKE', "%{$search}%"); // Filter berdasarkan nama
        }

        // Hitung total data sebelum filtering
        $totalData = $query->count();

        // Ambil data sesuai pagination DataTables
        $people = $query->offset($request->start)
            ->limit($request->length)
            ->get();

        // Jika tidak ada data
        if ($people->isEmpty()) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Konversi agama
        $religionOptions = [
            1 => 'Islam',
            2 => 'Kristen Katolik',
            3 => 'Kristen Protestan',
            4 => 'Hindu',
            5 => 'Buddha',
            6 => 'Konghucu',
        ];

        // Mapping data
        $filteredData = $people->map(function ($person) use ($religionOptions) {
            $coach = $person->coach;

            return [
                'id'    => $person->id,
                'identityNumber' => $person->identityNumber,
                'familyIdentityNumber' => $person->familyIdentityNumber,
                'phoneNumber'   => $person->phoneNumber,
                'fullName' => ucwords(strtolower($person->fullName)),
                'birthdate' => $person->birthdate,
                'age' => $person->age . ' Tahun',
                'religion' => $religionOptions[$person->religion] ?? 'Tidak ada data',
                'address' => ucwords(strtolower($person->streetAddress)),
                'province' => ucwords(strtolower(optional($person->province)->name)),
                'regencie' => ucwords(strtolower(optional($person->regencie)->name)),
                'district' => ucwords(strtolower(optional($person->district)->name)),
                'village' => ucwords(strtolower(optional($person->village)->name)),
                'imageProfile' => optional($person->document)->docsImageProfile,
                'regional_representative' => optional($coach->regional)->name,
                'sport' => optional($coach->sport)->name,
                'gender' => $person->gender,
                'updated_timestamp' => $person->updated_at,
                'documentId' => $person->documentId,
                'athleetId' => $coach->id,
            ];
        });

        // Return response sesuai format DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData->count(),
            'data' => $filteredData->values()
        ]);
    }

    public function getAthlete(Request $request)
    {
        // Ambil semua data Person beserta relasinya (Athlete, Document, dan regional_representative)
        $query = Person::with([
            'province',
            'regencie',
            'district',
            'village',
            'athlete.sport',
            'athlete.sportClass',
            'athlete.regional',
            'document'
        ])
            ->whereHas('athlete') // Pastikan hanya mengambil data yang punya relasi athlete
            ->orderBy('created_at', 'desc');

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('fullName', 'LIKE', "%{$search}%"); // Filter berdasarkan nama
        }

        // Hitung total data sebelum filtering
        $totalData = $query->count();

        // Ambil data sesuai pagination DataTables
        $people = $query->offset($request->start)
            ->limit($request->length)
            ->get();

        // Jika tidak ada data
        if ($people->isEmpty()) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
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

        // Mapping data
        $filteredData = $people->map(function ($person) use ($religionOptions) {
            $athlete = $person->athlete;
            $height = $athlete->height ? intval($athlete->height) . ' Cm' : 'Tidak ada data';
            $weight = $athlete->weight ? intval($athlete->weight) . ' Kg' : 'Tidak ada data';

            return [
                'id'                    => $person->id,
                'identityNumber'        => $person->identityNumber,
                'familyIdentityNumber'  => $person->familyIdentityNumber,
                'phoneNumber'           => $person->phoneNumber,
                'fullName'              => ucwords(strtolower($person->fullName)),
                'birthdate'             => $person->birthdate,
                'age'                   => $person->age . ' Tahun',
                'religion'              => $religionOptions[$person->religion] ?? 'Tidak ada data', // Konversi agama ke string
                'address'               => ucwords(strtolower($person->streetAddress)),
                'province'              => ucwords(strtolower(optional($person->province)->name)),
                'regencie'              => ucwords(strtolower(optional($person->regencie)->name)),
                'district'              => ucwords(strtolower(optional($person->district)->name)),
                'village'               => ucwords(strtolower(optional($person->village)->name)),
                'imageProfile'          => optional($person->document)->docsImageProfile,
                'regional_representative' => optional($athlete->regional)->name,
                'sport'                 => optional($athlete->sport)->name,
                'gender'                => $person->gender,
                'weight'                => $weight,
                'height'                => $height,
                'updated_timestamp'     => Carbon::parse($person->updated_at)->translatedFormat('d F Y'),
                'documentId'            => $person->documentId,
                'athleetId'             => $athlete->id,
            ];
        });

        // Return response sesuai format DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData->count(),
            'data' => $filteredData->values()
        ]);
    }

    public function getOfficial(Request $request)
    {
        $query = Person::with([
            'province',
            'regencie',
            'district',
            'village',
            'coach.sport',
            'coach.sportClass',
            'coach.regional',
            'document'
        ])->whereHas('coach', function ($query) {
            $query->whereIn('role', ['official', 'official_asisten']);
        })
        ->orderBy('created_at', 'desc');

        // Cek apakah ada parameter pencarian dari DataTables
        if (!empty($request->input('search')['value'])) {
            $search = $request->input('search')['value'];
            $query->where('fullName', 'LIKE', "%{$search}%"); // Filter berdasarkan nama
        }

        // Hitung total data sebelum filtering
        $totalData = $query->count();

        // Ambil data sesuai pagination DataTables
        $people = $query->offset($request->start)
        ->limit($request->length)
        ->get();

        // Jika tidak ada data
        if ($people->isEmpty()) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Konversi agama
        $religionOptions = [
                1 => 'Islam',
                2 => 'Kristen Katolik',
                3 => 'Kristen Protestan',
                4 => 'Hindu',
                5 => 'Buddha',
                6 => 'Konghucu',
            ];

        // Mapping data
        $filteredData = $people->map(function ($person) use ($religionOptions) {
            $coach = $person->coach;

            return [
                'id'    => $person->id,
                'identityNumber' => $person->identityNumber,
                'familyIdentityNumber' => $person->familyIdentityNumber,
                'phoneNumber'   => $person->phoneNumber,
                'fullName' => ucwords(strtolower($person->fullName)),
                'birthdate' => $person->birthdate,
                'age' => $person->age . ' Tahun',
                'religion' => $religionOptions[$person->religion] ?? 'Tidak ada data',
                'address' => ucwords(strtolower($person->streetAddress)),
                'province' => ucwords(strtolower(optional($person->province)->name)),
                'regencie' => ucwords(strtolower(optional($person->regencie)->name)),
                'district' => ucwords(strtolower(optional($person->district)->name)),
                'village' => ucwords(strtolower(optional($person->village)->name)),
                'imageProfile' => optional($person->document)->docsImageProfile,
                'regional_representative' => optional($coach->regional)->name,
                'sport' => optional($coach->sport)->name,
                'gender' => $person->gender,
                'updated_timestamp' => $person->updated_at,
                'documentId' => $person->documentId,
                'athleetId' => $coach->id,
            ];
        });

        // Return response sesuai format DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData->count(),
            'data' => $filteredData->values()
        ]);
    }



}
