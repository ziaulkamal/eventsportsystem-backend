<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Helper function untuk generate nama file Base32.
     */
    private function generateBase32FileName($file)
    {
        $extension = $file->getClientOriginalExtension(); // Ambil ekstensi file
        $base32Name = Str::random(40); // Generate random string Base32
        return $base32Name . '.' . $extension; // Gabungkan dengan ekstensi
    }

    /**
     * Helper function untuk simpan file.
     */
    private function saveFile($file, $folder)
    {
        // Cek apakah folder sudah ada
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder); // Buat folder jika belum ada
        }

        $fileName = $this->generateBase32FileName($file); // Generate nama file
        $filePath = $folder . '/' . $fileName; // Gabungkan folder dan nama file
        Storage::disk('public')->put($filePath, file_get_contents($file)); // Simpan file
        return $filePath; // Kembalikan path lengkap
    }

    /**
     * Menyimpan dokumen baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'imageProfile' => 'required|file|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            'familyProfile' => 'required|file|mimes:jpeg,png,jpg|max:5120',
            'ktpPhoto' => 'required|file|mimes:jpeg,png,jpg|max:5120',
            'selfiePhoto' => 'required|file|mimes:jpeg,png,jpg|max:5120',
            'userId' => 'nullable|uuid',
            'additionalFiles' => 'nullable|array',
            'additionalFiles.*.file' => 'file|mimes:jpeg,png,jpg,pdf|max:5120', // Max 5MB
            'additionalFiles.*.name' => 'required|string',
            'additionalFiles.*.type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Generate UUID untuk folder
            $folder = Str::uuid()->toString();

            // Simpan file utama
            $imageProfilePath = $this->saveFile($request->file('imageProfile'), $folder);
            $familyProfilePath = $this->saveFile($request->file('familyProfile'), $folder);
            $ktpPhotoPath = $this->saveFile($request->file('ktpPhoto'), $folder);
            $selfiePhotoPath = $this->saveFile($request->file('selfiePhoto'), $folder);

            // Simpan file tambahan
            $additionalFiles = [];
            if ($request->has('additionalFiles')) {
                foreach ($request->additionalFiles as $additionalFile) {
                    $filePath = $this->saveFile($additionalFile['file'], $folder);
                    $additionalFiles[] = [
                        'name' => $additionalFile['name'],
                        'type' => $additionalFile['type'],
                        'url' => $filePath,
                    ];
                }
            }

            // Simpan ke database
            $document = Document::create([
                'id' => $folder,
                'imageProfile' => $imageProfilePath,
                'familyProfile' => $familyProfilePath,
                'ktpPhoto' => $ktpPhotoPath,
                'selfiePhoto' => $selfiePhotoPath,
                'extra' => json_encode($additionalFiles), // Simpan file tambahan sebagai JSON
                'userId' => $request->userId,
            ]);

            return response()->json([
                'message' => 'Document created successfully!',
                'document' => $document,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create document.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengupdate dokumen yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'docsKtp'           => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'docsIjazah'        => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'docsSim'           => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'docsAkte'          => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'docsTransport'     => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'docsSelfieKtp'     => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'docsImageProfile'  => 'sometimes|file|mimes:jpeg,png,jpg|max:5120',
            'userId'            => 'nullable|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $document = Document::findOrFail($id);
            $folder = $document->id; // Gunakan UUID dokumen sebagai folder

            // Update file utama jika ada
            if ($request->hasFile('docsKtp')) {
                if ($document->docsKtp && Storage::disk('public')->exists($document->docsKtp)) {
                    Storage::disk('public')->delete($document->docsKtp);
                }
                $document->docsKtp = $this->saveFile($request->file('docsKtp'), $folder);
            }
            if ($request->hasFile('docsIjazah')) {
                if ($document->docsIjazah && Storage::disk('public')->exists($document->docsIjazah)) {
                    Storage::disk('public')->delete($document->docsIjazah);
                }
                $document->docsIjazah = $this->saveFile($request->file('docsIjazah'), $folder);
            }
            if ($request->hasFile('docsSim')) {
                if ($document->docsSim && Storage::disk('public')->exists($document->docsSim)) {
                    Storage::disk('public')->delete($document->docsSim);
                }
                $document->docsSim = $this->saveFile($request->file('docsSim'), $folder);
            }
            if ($request->hasFile('docsAkte')) {
                if ($document->docsAkte && Storage::disk('public')->exists($document->docsAkte)) {
                    Storage::disk('public')->delete($document->docsAkte);
                }
                $document->docsAkte = $this->saveFile($request->file('docsAkte'), $folder);
            }
            if ($request->hasFile('docsTransport')) {
                if ($document->docsTransport && Storage::disk('public')->exists($document->docsTransport)) {
                    Storage::disk('public')->delete($document->docsTransport);
                }
                $document->docsTransport = $this->saveFile($request->file('docsTransport'), $folder);
            }
            if ($request->hasFile('docsSelfieKtp')) {
                if ($document->docsSelfieKtp && Storage::disk('public')->exists($document->docsSelfieKtp)) {
                    Storage::disk('public')->delete($document->docsSelfieKtp);
                }
                $document->docsSelfieKtp = $this->saveFile($request->file('docsSelfieKtp'), $folder);
            }
            if ($request->hasFile('docsImageProfile')) {
                if ($document->docsImageProfile && Storage::disk('public')->exists($document->docsImageProfile)) {
                    Storage::disk('public')->delete($document->docsImageProfile);
                }
                $document->docsImageProfile = $this->saveFile($request->file('docsImageProfile'), $folder);
            }


            // Update userId jika ada
            if ($request->has('userId')) {
                $document->userId = $request->userId;
            }

            $document->save();

            return response()->json([
                'message' => 'Document updated successfully!',
                'document' => $document,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update document.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return response()->json($document);
    }





}
