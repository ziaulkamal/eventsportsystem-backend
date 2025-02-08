<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\DefaultUserSeeder;
use Database\Seeders\KemendagriWilayahSeeder;
use Database\Seeders\MasterAuthSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::deleteDirectory('public');
        $this->call([
            KemendagriWilayahSeeder::class,
            DefaultUserSeeder::class,
            MasterSportSeeder::class,
        ]);
    }
}
