<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KemendagriWilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sql_path = base_path('database/db_mendagri.sql'); // Sesuaikan nama file SQL
        DB::unprepared(file_get_contents($sql_path));
    }
}
