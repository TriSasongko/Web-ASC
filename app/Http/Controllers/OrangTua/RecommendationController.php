<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function respond(Request $request, ClassRecommendation $recommendation)
    {
        abort_unless($recommendation->student->parent_id === auth()->id(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:diterima,ditolak'],
        ]);

        $recommendation->update(['status' => $validated['status']]);

        return back()->with('success', 'Respons rekomendasi tersimpan.');
    }
}
