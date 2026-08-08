<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index(Request $request)
    {
        $developments = Development::with(['student', 'schoolClass.program', 'coach'])
            ->when($request->search, fn($q) => $q->whereHas('student', fn($s) => $s->where('full_name', 'like', '%'.$request->search.'%')))
            ->latest()
            ->paginate(15);

        return view('admin.developments.index', compact('developments'));
    }

    public function destroy(Development $development)
    {
        $development->delete();
        return back()->with('success', 'Data perkembangan berhasil dihapus.');
    }
}
