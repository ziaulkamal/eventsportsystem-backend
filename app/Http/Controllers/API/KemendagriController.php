<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kemendagri\Districts;
use App\Models\Kemendagri\Provinces;
use App\Models\Kemendagri\Regencies;
use App\Models\Kemendagri\Villages;
use Illuminate\Http\Request;

class KemendagriController extends Controller
{
     /**
     * Get all provinces.
     */
    public function getProvinces()
    {
        $provinces = Provinces::all();
        return response()->json($provinces->pluck('id', 'name'));
    }

    /**
     * Get regencies for a specific province.
     */
    public function getRegencies($provinceId)
    {
        // dd($provinceId);
        $regencies = Regencies::where('province_id', $provinceId)->get();
        return response()->json($regencies->pluck('id', 'name'));
    }

    /**
     * Get districts for a specific regency.
     */
    public function getDistricts($regencyId)
    {
        $districts = Districts::where('regency_id', $regencyId)->get();
        // dd($districts);
        return response()->json($districts->pluck('id', 'name'));
    }

    /**
     * Get villages for a specific district.
     */
    public function getVillages($districtId)
    {
        $district = Villages::where('district_id', $districtId)->get();
        return response()->json($district->pluck('id', 'name'));
    }

    public function getKontingen() {
        $data = Regencies::all();
        // Transform data untuk hanya mengambil 'id' dan 'name'
        $result = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });

        return response()->json($result);
    }
}
