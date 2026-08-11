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
            'phone' => '6281234567890',
            'address' => 'Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Kota Bandar Lampung, Lampung 35145',
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
