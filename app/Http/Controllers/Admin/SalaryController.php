<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachSalarySetting;
use App\Models\SalaryPayment;
use App\Models\SalarySetting;
use App\Models\User;
use App\Services\SalaryService;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $service = new SalaryService;
        $settings = SalarySetting::current();
        $unpaidByUser = $service->unpaidForAll();

        $coaches = User::where('role', 'pelatih')
            ->with(['salarySetting', 'salaryPayments'])
            ->orderBy('name')
            ->get()
            ->map(function (User $coach) use ($service, $unpaidByUser) {
                $recap = $unpaidByUser->get($coach->id, ['sessions' => collect(), 'total' => 0]);

                return (object) [
                    'id' => $coach->id,
                    'name' => $coach->name,
                    'session_limit' => $service->sessionLimitForCoach($coach),
                    'sessions' => $recap['sessions'],
                    'total' => $recap['total'],
                    'payments' => $coach->salaryPayments,
                ];
            });

        $limitOptions = [8, 12, 24];

        return view('admin.salaries.index', compact('settings', 'coaches', 'limitOptions'));
    }

    public function updateRates(Request $request)
    {
        $validated = $request->validate([
            'rate_reguler_satu' => ['required', 'integer', 'min:0'],
            'rate_reguler_dua_plus' => ['required', 'integer', 'min:0'],
            'rate_paralel_dua' => ['required', 'integer', 'min:0'],
            'rate_paralel_banyak' => ['required', 'integer', 'min:0'],
        ]);

        SalarySetting::current()->update($validated);

        return back()->with('success', 'Nominal honor berhasil diperbarui.');
    }

    public function updateLimit(Request $request, User $coach)
    {
        $validated = $request->validate([
            'session_limit' => ['required', 'integer', 'in:8,12,24'],
        ]);

        CoachSalarySetting::updateOrCreate(
            ['user_id' => $coach->id],
            ['session_limit' => $validated['session_limit']]
        );

        return back()->with('success', "Batas honor {$coach->name} diperbarui.");
    }

    public function pay(Request $request, User $coach)
    {
        $paid = (new SalaryService)->markPaid($coach);

        if (! $paid) {
            return back()->with('error', 'Tidak ada honor yang belum dibayar.');
        }

        return back()->with('success', "Honor {$coach->name} berhasil dicatat sebagai dibayar.");
    }

    public function destroyPayment(SalaryPayment $payment)
    {
        $payment->delete();

        return back()->with('success', 'Catatan pembayaran dihapus, batch honor dibuka kembali.');
    }
}
