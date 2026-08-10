<?php

namespace Tests\Feature;

use App\Models\CoachNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelatihNoteTest extends TestCase
{
    use RefreshDatabase;

    private function makeCoach(): User
    {
        return User::factory()->create(['role' => 'pelatih', 'is_active' => true]);
    }

    public function test_coach_can_create_note_and_see_it_on_dashboard()
    {
        $coach = $this->makeCoach();

        $this->actingAs($coach)
            ->post(route('pelatih.notes.store'), ['content' => 'Catatan rahasia saya', 'note_date' => '2026-08-01 00:00:00'])
            ->assertRedirect(route('pelatih.dashboard'));

        $this->assertDatabaseHas('coach_notes', [
            'coach_id' => $coach->id,
            'content' => 'Catatan rahasia saya',
            'note_date' => '2026-08-01 00:00:00',
        ]);

        $this->actingAs($coach)
            ->get(route('pelatih.dashboard'))
            ->assertOk()
            ->assertSee('Catatan rahasia saya');
    }

    public function test_coach_cannot_update_or_delete_another_coaches_note()
    {
        $owner = $this->makeCoach();
        $other = $this->makeCoach();

        $note = CoachNote::create([
            'coach_id' => $owner->id,
            'content' => 'Catatan pribadi owner',
        ]);

        $this->actingAs($other)
            ->patch(route('pelatih.notes.update', $note), ['content' => 'Curi catatan'])
            ->assertNotFound();

        $this->actingAs($other)
            ->delete(route('pelatih.notes.destroy', $note))
            ->assertNotFound();

        $this->assertDatabaseHas('coach_notes', ['id' => $note->id, 'content' => 'Catatan pribadi owner']);
    }

    public function test_owner_can_update_and_delete_own_note()
    {
        $coach = $this->makeCoach();

        $note = CoachNote::create([
            'coach_id' => $coach->id,
            'content' => 'Versi awal',
        ]);

        $this->actingAs($coach)
            ->patch(route('pelatih.notes.update', $note), ['content' => 'Versi baru', 'note_date' => '2026-08-02 00:00:00'])
            ->assertRedirect(route('pelatih.dashboard'));

        $this->assertDatabaseHas('coach_notes', ['id' => $note->id, 'content' => 'Versi baru', 'note_date' => '2026-08-02 00:00:00']);

        $this->actingAs($coach)
            ->delete(route('pelatih.notes.destroy', $note))
            ->assertRedirect(route('pelatih.dashboard'));

        $this->assertDatabaseMissing('coach_notes', ['id' => $note->id]);
    }

    public function test_admin_and_parent_cannot_access_note_endpoints()
    {
        $coach = $this->makeCoach();
        $note = CoachNote::create(['coach_id' => $coach->id, 'content' => 'Catatan']);

        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $parent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);

        foreach ([$admin, $parent] as $user) {
            $this->actingAs($user)
                ->post(route('pelatih.notes.store'), ['content' => 'x'])
                ->assertForbidden();

            $this->actingAs($user)
                ->patch(route('pelatih.notes.update', $note), ['content' => 'x'])
                ->assertForbidden();

            $this->actingAs($user)
                ->delete(route('pelatih.notes.destroy', $note))
                ->assertForbidden();
        }
    }
}
