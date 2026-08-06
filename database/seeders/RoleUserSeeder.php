<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin ASC',
            'email' => 'admin@asc.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Coach Contoh',
            'email' => 'pelatih@asc.test',
            'password' => Hash::make('password'),
            'role' => 'pelatih',
        ]);

        User::create([
            'name' => 'Orang Tua Contoh',
            'email' => 'ortu@asc.test',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
        ]);
    }
}
