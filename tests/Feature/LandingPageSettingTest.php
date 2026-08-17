<?php

namespace Tests\Feature;

use App\Models\LandingCoach;
use App\Models\LandingGalleryImage;
use App\Models\LandingProgram;
use App\Models\LandingSetting;
use App\Models\User;
use Database\Seeders\LandingPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        $this->get('/tentang')->assertOk()->assertSee('Tentang AantassenaSwimClub');
        $this->get('/program')->assertOk()->assertSee('Program Kelas Kami')->assertSee('Private');
        $this->get('/galeri')->assertOk()->assertSee('Galeri Kegiatan');
        $this->get('/kontak')->assertOk()->assertSee('Jam Operasional');
        $this->get('/faq')->assertOk();
    }

    public function test_admin_can_open_settings_tabs(): void
    {
        $admin = $this->makeAdmin();

        foreach (['hero', 'tentang', 'program', 'galeri', 'jadwal', 'kontak'] as $tab) {
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
            'hero_image' => UploadedFile::fake()->image('hero.jpg', 1200, 600),
            'hero_side_image' => UploadedFile::fake()->image('side.jpg', 800, 600),
            'hero_side_image_alt' => '',
            'hero_cta_primary' => 'Daftar',
            'hero_cta_secondary' => 'Program',
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'hero']));

        $this->assertSame('Judul Baru', LandingSetting::get('hero_title'));
        $this->assertNotNull(LandingSetting::get('hero_image'));
        $this->assertNotNull(LandingSetting::get('hero_side_image'));
        $this->assertTrue(Storage::disk('public')->exists(LandingSetting::get('hero_image')));
        $this->assertTrue(Storage::disk('public')->exists(LandingSetting::get('hero_side_image')));

        $this->get('/')->assertOk()->assertSee('Judul Baru')->assertSee('Highlight Baru');

        $this->actingAs($admin)->put(route('admin.settings.hero'), [
            'hero_title' => 'Judul Baru',
            'hero_subtitle' => 'Deskripsi baru untuk hero.',
            'remove_hero_image' => 1,
        ])->assertRedirect();

        $this->assertNull(LandingSetting::get('hero_image'));
    }

    public function test_admin_can_update_tentang_image_via_upload(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.settings.tentang'), [
            'tentang_heading' => 'Tentang ASC',
            'tentang_text' => 'Deskripsi tentang.',
            'tentang_visi' => 'Visi.',
            'tentang_image' => UploadedFile::fake()->image('tentang.jpg', 800, 600),
        ])->assertRedirect();

        $this->assertNotNull(LandingSetting::get('tentang_image'));
        $this->assertTrue(Storage::disk('public')->exists(LandingSetting::get('tentang_image')));

        $this->get('/tentang')->assertOk();
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
            'photo' => UploadedFile::fake()->image('coach.jpg', 100, 100),
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'tentang']));

        $coach = LandingCoach::where('name', 'Coach Baru')->first();
        $this->assertNotNull($coach);
        $this->assertNotNull($coach->photo_url);
        $this->assertTrue(Storage::disk('public')->exists($coach->photo_url));

        $this->actingAs($admin)->put(route('admin.settings.coaches.update', $coach), [
            'name' => 'Coach Diubah',
            'position' => 'Senior Coach',
            'description' => 'Deskripsi baru.',
            'photo' => UploadedFile::fake()->image('coach-baru.jpg', 100, 100),
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect();

        $fresh = $coach->fresh();
        $this->assertSame('Coach Diubah', $fresh->name);
        $this->assertNotSame($fresh->photo_url, $coach->photo_url);
        $this->assertTrue(Storage::disk('public')->exists($fresh->photo_url));

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
            'photo' => UploadedFile::fake()->image('foto.jpg', 400, 300),
            'title' => 'Foto Baru',
            'description' => 'Deskripsi foto.',
            'category' => 'Latihan',
            'aspect' => 'square',
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'galeri']));

        $image = LandingGalleryImage::where('title', 'Foto Baru')->first();
        $this->assertNotNull($image);
        $this->assertNotNull($image->image_url);
        $this->assertTrue(Storage::disk('public')->exists($image->image_url));

        $this->actingAs($admin)->delete(route('admin.settings.gallery.destroy', $image))->assertRedirect();
        $this->assertDatabaseMissing('landing_gallery', ['id' => $image->id]);
    }

    public function test_admin_can_add_video_gallery_item_via_url(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->post(route('admin.settings.gallery.store'), [
            'image_url' => 'https://www.youtube.com/embed/abc123',
            'title' => 'Video Baru',
            'aspect' => 'video',
            'sort_order' => 9,
            'is_active' => 1,
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'galeri']));

        $this->assertDatabaseHas('landing_gallery', ['title' => 'Video Baru']);
    }

    public function test_admin_can_update_jadwal_settings_and_it_reflects_on_homepage(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.settings.jadwal'), [
            'jadwal_heading' => 'Jadwal Reguler ASC',
            'jadwal_subtitle' => 'Jadwal latihan kelas reguler setiap pekan.',
            'rows' => [
                ['day' => 'Senin', 'time' => '15:00 - 16:30', 'program' => 'Reguler', 'location' => 'Kolam Unila'],
                ['day' => 'Sabtu', 'time' => '07:00 - 08:30', 'program' => 'Mini Reguler', 'location' => 'Kolam Unila'],
            ],
        ])->assertRedirect(route('admin.settings.edit', ['tab' => 'jadwal']));

        $this->assertSame('Jadwal Reguler ASC', LandingSetting::get('jadwal_heading'));

        $this->get('/')->assertOk()
            ->assertSee('Jadwal Reguler ASC')
            ->assertSee('Jadwal latihan kelas reguler setiap pekan.')
            ->assertSee('Senin', false)
            ->assertSee('15:00 - 16:30')
            ->assertSee('Kolam Unila');
    }

    public function test_invalid_jadwal_rows_are_rejected(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.settings.jadwal'), [
            'jadwal_heading' => 'Jadwal',
            'rows' => [
                ['day' => '', 'time' => '15:00', 'program' => 'Reguler', 'location' => 'Kolam'],
            ],
        ])->assertSessionHasErrors('rows.0.day');

        $this->assertNotSame('Jadwal', LandingSetting::get('jadwal_heading'));

        $this->actingAs($admin)->put(route('admin.settings.jadwal'), [
            'jadwal_heading' => 'Jadwal Tanpa Baris',
            'rows' => [],
        ])->assertSessionHasErrors('rows');
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
