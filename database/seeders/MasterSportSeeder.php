<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterSportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'ANGGAR',
            'ANGKAT BESI',
            'ATLETIK',
            'BALAP SEPEDA',
            'BERMOTOR',
            'BOLA BASKET',
            'BOLA VOLLY',
            'BADMINTON',
            'DAYUNG',
            'FUTSAL',
            'KARATE',
            'KEMPO',
            'LAYAR',
            'MENEMBAK',
            'MUAYTHAI',
            'PANAHAN',
            'PANJAT TEBING',
            'PENCAK SILAT',
            'PENTAQUE',
            'SEPAK BOLA',
            'SEPAK TAKRAW',
            'SEPATU RODA',
            'TAEKWONDO',
            'TARUNG DERAJAT',
            'TENIS LAPANGAN',
            'TENIS MEJA',
            'TINJU',
            'WUSHU',
            'ARUNG JERAM'
        ];

        foreach ($data as $name) {
            DB::table('sports')->insert([
                'id' => Str::uuid(),
                'name' => $name,
                'description' => '-',
                'imageId' => null,
                'status' => true,
                'userId' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
