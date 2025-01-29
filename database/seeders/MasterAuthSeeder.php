<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = 99;
        $id   = Str::uuid();

        DB::table('levels')->insert([
            'id' => $id,
            'role' => $role,
            'name' => 'Superadmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id'        => Str::uuid(),
            'username'  => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => Hash::make('admin'),
            'levelId'   => $id,
            'status'    => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
