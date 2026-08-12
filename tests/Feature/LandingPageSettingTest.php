<?php

namespace Tests\Feature;

use App\Models\LandingCoach;
use App\Models\LandingGalleryImage;
use App\Models\LandingProgram;
use App\Models\LandingSetting;
use App\Models\User;
use Database\Seeders\LandingPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LandingPageSeeder::class);
    }

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_public_landing_pages_render_seeded_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Belajar Renang Bersama')
            ->assertSee('Temui Coach Kami')
            ->assertSee('Galeri Kegiatan');

        $this->get('/tentang')->assertOk()->assertSee('Tentang Antasena Swimming Club');
        $this->get('/program')->assertOk()->assertSee('Program Kelas Kami')->assertSee('Private');
        $this->get('/galeri')->assertOk()->assertSee('Galeri Kegiatan');
        $this->get('/kontak')->assertOk()->assertSee('Jam Operasional');
        $this->get('/faq')->assertOk();
    }

    public function test_admin_can_open_settings_tabs(): void
    {
        $admin = $this->makeAdmin();

        foreach (['hero', 'tentang', 'program', 'galeri', 'kontak'] as $tab) {
            $this->actingAs($admin)->get(route('admin.settings.edit', ['tab' => $tab]))
                ->assertOk();
        }
    }

    public function test_non_admin_cannot_access_settings(): void
    {
        $pelatih = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $this->actingAs($pelatih)->get(route('admin.settings.edit'))->assertForbidden();
    }

    public function test_admin_can_update_hero_settings_and_it_reflects_on_homepage(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.settings.hero'), [
            'hero_title' => 'Judul Baru',
            'hero_highlight' => 'Highlight Baru',
            'hero_subtitle' => 'Deskripsi baru untuk hero.',
            'hero_image' => '',
            'hero_side_image' => '',
            'hero_side_image_alt' => '',
            'hero_cta_primary' => 'Daftar',
            'hero_cta_secondary' => 'Program',
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'hero']));

        $this->assertSame('Judul Baru', LandingSetting::get('hero_title'));

        $this->get('/')->assertOk()->assertSee('Judul Baru')->assertSee('Highlight Baru');
    }

    public function test_admin_can_update_kontak_settings(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.settings.kontak'), [
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'kontak_email' => 'info@asc.test',
            'kontak_instagram' => 'https://www.instagram.com/asc_baru/',
            'kontak_instagram_handle' => '@asc_baru',
            'kontak_maps_url' => 'https://www.google.com/maps/embed?pb=test',
            'kontak_hours_weekday' => 'Senin – Jumat: 09.00 – 18.00',
            'kontak_hours_weekend' => 'Sabtu – Minggu: 08.00 – 17.00',
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'kontak']));

        $this->assertSame('info@asc.test', LandingSetting::get('kontak_email'));

        $this->get('/kontak')->assertOk()->assertSee('info@asc.test');
    }

    public function test_admin_can_crud_coach(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->post(route('admin.settings.coaches.store'), [
            'name' => 'Coach Baru',
            'position' => 'Coach',
            'description' => 'Deskripsi coach baru.',
            'photo_url' => '',
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'tentang']));

        $coach = LandingCoach::where('name', 'Coach Baru')->first();
        $this->assertNotNull($coach);

        $this->actingAs($admin)->put(route('admin.settings.coaches.update', $coach), [
            'name' => 'Coach Diubah',
            'position' => 'Senior Coach',
            'description' => 'Deskripsi baru.',
            'photo_url' => '',
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect();

        $this->assertSame('Coach Diubah', $coach->fresh()->name);

        $this->actingAs($admin)->delete(route('admin.settings.coaches.destroy', $coach))->assertRedirect();
        $this->assertDatabaseMissing('landing_coaches', ['id' => $coach->id]);
    }

    public function test_admin_can_crud_program(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->post(route('admin.settings.programs.store'), [
            'name' => 'Program Baru',
            'subtitle' => '1 Coach : 1 Siswa',
            'price' => 100000,
            'billing_unit' => '/sesi',
            'features' => "Fitur satu\nFitur dua",
            'badge' => '',
            'button_label' => 'Pilih',
            'featured' => 0,
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'program']));

        $program = LandingProgram::where('name', 'Program Baru')->first();
        $this->assertNotNull($program);
        $this->assertSame(['Fitur satu', 'Fitur dua'], $program->featureList());

        $this->actingAs($admin)->delete(route('admin.settings.programs.destroy', $program))->assertRedirect();
        $this->assertDatabaseMissing('landing_programs', ['id' => $program->id]);
    }

    public function test_admin_can_crud_gallery(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->post(route('admin.settings.gallery.store'), [
            'image_url' => 'https://example.com/foto.jpg',
            'title' => 'Foto Baru',
            'description' => 'Deskripsi foto.',
            'category' => 'Latihan',
            'aspect' => 'square',
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'galeri']));

        $image = LandingGalleryImage::where('title', 'Foto Baru')->first();
        $this->assertNotNull($image);

        $this->actingAs($admin)->delete(route('admin.settings.gallery.destroy', $image))->assertRedirect();
        $this->assertDatabaseMissing('landing_gallery', ['id' => $image->id]);
    }

    public function test_invalid_kontak_email_is_rejected(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.settings.kontak'), [
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'kontak_email' => 'bukan-email',
        ])->assertSessionHasErrors('kontak_email');

        $this->assertNotSame('bukan-email', LandingSetting::get('kontak_email'));
    }
}
