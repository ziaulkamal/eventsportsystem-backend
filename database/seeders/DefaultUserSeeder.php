<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultUserSeeder extends Seeder
{
    public function run() {

        $uuid = Str::uuid();
        Level::insert([
            'id'    => 1,
            'name'  => 'root',
            'role'  => 1,
        ]);

        Person::insert([
            'id'                => $uuid,
            'fullName'          => 'Ziaul Kamal',
            'identityNumber'    => '0',
            'gender'            => 'male'
        ]);

        User::insert([
            'id'        => Str::uuid(),
            'peopleId'  => $uuid,
            'levelId'   => 1,
            'username'  => 'root',
            'email'     => 'whoami@root.test',
            'password'  => Hash::make('root'),
            'status'    => 'active'
        ]);
    }

}
