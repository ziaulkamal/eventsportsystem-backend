<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Fungsi untuk mendapatkan atau membuat access token baru
    public function getAccessToken()
    {
        // Cek apakah token sudah ada di database dan valid
        $existingToken = AccessToken::where('expires_at', '>', Carbon::now())->first();

        if ($existingToken && $existingToken->hit_count < 500) {
            // Jika token ada dan belum mencapai batas hit
            $existingToken->hit_count += 1;
            $existingToken->save();

            return response()->json([
                'access_token' => $existingToken->access_token,
                'expires_in' => $existingToken->expires_at->diffInSeconds(Carbon::now()),
                'token_type' => 'Bearer',
            ]);
        }

        // Jika tidak ada token atau sudah expired, buat token baru
        return $this->generateNewAccessToken();
    }

    // Fungsi untuk membuat token baru
    private function generateNewAccessToken()
    {
        $clientId = env('SATUSEHAT_CLIENT_ID');
        $clientSecret = env('SATUSEHAT_CLIENT_SECRET');
        $baseUrl = env('SATUSEHAT_BASE_URL');  // Ambil URL dari .env

        try {
            // Inisialisasi Guzzle Client
            $client = new Client();

            // Konfigurasi request
            $options = [
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ],
                'verify' => false,  // Nonaktifkan verifikasi SSL untuk pengujian
            ];

            // Kirim request ke API untuk mendapatkan token
            $response = $client->post("{$baseUrl}/oauth2/v1/accesstoken?grant_type=client_credentials", $options);

            // Ambil response body
            $data = json_decode($response->getBody(), true);

            // Simpan token baru ke database
            $newToken = AccessToken::create([
                'access_token' => $data['access_token'],
                'hit_count' => 1,  // Set hit pertama
                'expires_at' => Carbon::now()->addSeconds($data['expires_in']),
            ]);

            // Kembalikan response token
            return response()->json([
                'access_token' => $newToken->access_token,
                'expires_in' => $data['expires_in'],
                'token_type' => 'Bearer',
            ]);
        } catch (RequestException $e) {
            // Handle error
            $errorResponse = $e->getResponse();
            $errorMessage = $errorResponse ? $errorResponse->getBody()->getContents() : $e->getMessage();

            return response()->json([
                'error' => 'Failed to fetch access token',
                'message' => $errorMessage,
            ], 500);
        }
    }

    // Fungsi untuk mengambil data pasien berdasarkan NIK
    public function getIdentityPeople($nik)
    {
        // Ambil token yang valid
        $existingToken = AccessToken::where('expires_at', '>', Carbon::now())->first();

        if (!$existingToken) {
            // Token tidak ada atau sudah kadaluarsa, buat token baru
            $this->getAccessToken();

            // Ambil token baru dari database
            $existingToken = AccessToken::where('expires_at', '>', Carbon::now())->first();
        }

        // Menggunakan Bearer token yang ada untuk permintaan ke endpoint Patient
        $client = new Client();
        $headers = [
            'Authorization' => "Bearer {$existingToken->access_token}",
            'Content-Type' => 'application/json',
        ];

        // URL endpoint untuk data pasien, menggunakan URL dari .env
        $baseUrl = env('SATUSEHAT_BASE_URL');
        $url = "{$baseUrl}/fhir-r4/v1/Patient?identifier=https://fhir.kemkes.go.id/id/nik|{$nik}";

        try {
            // Kirim permintaan ke endpoint Patient
            $response = $client->get($url, [
                'headers' => $headers,
                'verify' => false,  // Nonaktifkan verifikasi SSL
            ]);

            // Ambil dan decode data pasien
            $patientData = json_decode($response->getBody(), true);

            // Periksa apakah ada data di dalam entry
            if (!empty($patientData['entry']) && isset($patientData['entry'][0]['resource'])) {
                $resource = $patientData['entry'][0]['resource'];

                // Buat format baru
                $formattedData = [
                    'id' => $resource['id'],
                    'name' => $resource['name'][0]['text'] ?? null,
                ];

                return response()->json($formattedData);
            }

            // Jika tidak ada data entry
            return response()->json([
                'error' => 'No data found',
            ], 404);
        } catch (RequestException $e) {
            // Tangani error
            $errorResponse = $e->getResponse();
            $errorMessage = $errorResponse ? $errorResponse->getBody()->getContents() : $e->getMessage();

            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $errorMessage,
            ], 500);
        }
    }



    public function getIdentityPeopleByAttributes(Request $request)
    {
        // Ambil parameter dari query string
        $name = $request->query('name');
        $birthdate = $request->query('birthdate');
        $gender = $request->query('gender');
        $personIdentity = $request->query('personIdentity'); // Ambil personIdentity dari query parameter

        // Validasi parameter (opsional)
        if (!$name || !$birthdate || !$gender) {
            return response()->json([
                'error' => 'Missing required parameters',
                'message' => 'Please provide name, birthdate, and gender.',
            ], 400);
        }

        // Ambil token yang valid
        $existingToken = AccessToken::where('expires_at', '>', Carbon::now())->first();

        if (!$existingToken) {
            // Token tidak ada atau sudah kadaluarsa, buat token baru
            $this->getAccessToken();

            // Ambil token baru dari database
            $existingToken = AccessToken::where('expires_at', '>', Carbon::now())->first();
        }

        // Menggunakan Bearer token yang ada untuk permintaan ke endpoint Patient
        $client = new Client();
        $headers = [
            'Authorization' => "Bearer {$existingToken->access_token}",
            'Content-Type' => 'application/json',
        ];

        // URL endpoint untuk data pasien
        $baseUrl = env('SATUSEHAT_BASE_URL');

        // Membuat URL untuk pencarian berdasarkan nama, tanggal lahir, dan jenis kelamin
        $url = "{$baseUrl}/fhir-r4/v1/Patient?name={$name}&birthdate={$birthdate}&gender={$gender}";

        try {
            // Kirim permintaan ke endpoint Patient
            $response = $client->get($url, [
                'headers' => $headers,
                'verify' => false,  // Nonaktifkan verifikasi SSL
            ]);

            // Ambil data JSON yang diterima
            $patientData = json_decode($response->getBody(), true);

            // Jika personIdentity diberikan, filter data berdasarkan personIdentity
            if ($personIdentity) {
                // Periksa jika entry ada dan valid
                if (isset($patientData['entry']) && is_array($patientData['entry'])) {
                    // Filter entries berdasarkan personIdentity jika diberikan
                    $filteredEntries = array_filter($patientData['entry'], function ($entry) use ($personIdentity) {
                        // Cek jika ID resource sesuai dengan personIdentity
                        return isset($entry['resource']['id']) && $entry['resource']['id'] === $personIdentity;
                    });

                    // Konversi hasil filter menjadi array (array_filter mengembalikan array berindeks)
                    $filteredEntries = array_values($filteredEntries);

                    // Jika ada data yang sesuai, ambil hanya bagian address.extension yang pertama dan kemudian extension di dalamnya
                    if (!empty($filteredEntries)) {
                        $filteredAddressData = [];
                        foreach ($filteredEntries as $entry) {
                            // Periksa apakah ada address dan extension pertama yang valid
                            if (isset($entry['resource']['address'][0]['extension'][0]['extension'])) {
                                $addressExtensions = $entry['resource']['address'][0]['extension'][0]['extension']; // Ambil extension dalam extension pertama

                                // Ambil extension yang relevan (misalnya, province, city, district, village)
                                foreach ($addressExtensions as $extension) {
                                    if (isset($extension['url']) && isset($extension['valueCode'])) {
                                        switch ($extension['url']) {
                                            case 'province':
                                                $filteredAddressData['province'] = $extension['valueCode'];
                                                break;
                                            case 'city':
                                                $filteredAddressData['city'] = $extension['valueCode'];
                                                break;
                                            case 'district':
                                                $filteredAddressData['district'] = $extension['valueCode'];
                                                break;
                                            case 'village':
                                                $filteredAddressData['village'] = $extension['valueCode'];
                                                break;
                                        }
                                    }
                                }
                            }
                        }

                        // Jika data address.extension ditemukan, kembalikan data
                        if (!empty($filteredAddressData)) {
                            return response()->json($filteredAddressData);
                        } else {
                            // Jika tidak ada extension yang relevan ditemukan
                            return response()->json([
                                'error' => 'No relevant address extension data found',
                                'message' => 'No relevant address extension data (province, city, district, village) found in the first extension.',
                            ], 404);
                        }
                    }

                    // Jika tidak ada data yang sesuai dengan personIdentity
                    return response()->json([
                        'error' => 'No matching data found for personIdentity',
                        'message' => 'No entry contains the specified personIdentity.',
                    ], 404);
                } else {
                    return response()->json([
                        'error' => 'Invalid entry data',
                        'message' => 'No entries available or invalid structure.',
                    ], 400);
                }
            }

            // Jika tidak ada filter personIdentity, kembalikan data entry seperti biasa
            return response()->json($patientData['entry']);
        } catch (RequestException $e) {
            // Tangani error
            $errorResponse = $e->getResponse();
            $errorMessage = $errorResponse ? $errorResponse->getBody()->getContents() : $e->getMessage();

            return response()->json([
                'error' => 'Failed to fetch patient data',
                'message' => $errorMessage,
            ], 500);
        }
    }




}
