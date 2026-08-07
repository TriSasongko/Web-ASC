<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['name' => 'Private', 'slug' => 'private', 'max_students' => 3, 'total_sessions' => 8, 'price' => 500000, 'billing_type' => 'per_paket'],
            ['name' => 'Mini Private', 'slug' => 'mini-private', 'max_students' => 3, 'total_sessions' => 4, 'price' => 300000, 'billing_type' => 'per_paket'],
            ['name' => 'Reguler', 'slug' => 'reguler', 'max_students' => 4, 'total_sessions' => 8, 'price' => 350000, 'billing_type' => 'per_paket'],
            ['name' => 'Mini Reguler', 'slug' => 'mini-reguler', 'max_students' => 4, 'total_sessions' => 4, 'price' => 200000, 'billing_type' => 'per_paket'],
            ['name' => 'Kompetitif', 'slug' => 'kompetitif', 'max_students' => null, 'total_sessions' => null, 'price' => 300000, 'billing_type' => 'per_bulan'],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
