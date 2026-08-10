<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\CoachNote;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        CoachNote::create([
            'coach_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('pelatih.dashboard')->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function update(Request $request, CoachNote $note)
    {
        abort_unless($note->coach_id === auth()->id(), 404);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $note->update($validated);

        return redirect()->route('pelatih.dashboard')->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroy(CoachNote $note)
    {
        abort_unless($note->coach_id === auth()->id(), 404);

        $note->delete();

        return redirect()->route('pelatih.dashboard')->with('success', 'Catatan berhasil dihapus.');
    }
}
