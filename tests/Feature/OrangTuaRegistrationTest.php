<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrangTuaRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_child_registration_form_prefills_parent_address(): void
    {
        $parent = User::factory()->create([
            'role' => 'orang_tua',
            'address' => 'Jl. Melati No. 12',
            'is_active' => true,
        ]);

        $response = $this->actingAs($parent)->get(route('orangtua.registrations.create'));

        $response->assertOk();
        $response->assertSee('Jl. Melati No. 12');
    }

    public function test_parent_address_is_saved_to_child_registration(): void
    {
        $parent = User::factory()->create([
            'role' => 'orang_tua',
            'address' => 'Jl. Melati No. 12',
            'is_active' => true,
        ]);

        $program = Program::create([
            'name' => 'Reguler',
            'slug' => 'reguler',
            'price' => 500000,
            'billing_type' => 'per_bulan',
        ]);

        $this->actingAs($parent)->post(route('orangtua.registrations.store'), [
            'phone' => '081234567890',
            'full_name' => 'Anak Test',
            'birth_place' => 'Jakarta',
            'birth_date' => '2015-01-01',
            'gender' => 'L',
            'weight' => '25',
            'height' => '120',
            'address' => 'Jl. Melati No. 12',
            'program_id' => $program->id,
        ])->assertRedirect(route('orangtua.registrations.index'));

        $this->assertDatabaseHas('students', [
            'parent_id' => $parent->id,
            'full_name' => 'Anak Test',
            'address' => 'Jl. Melati No. 12',
        ]);
    }
}
