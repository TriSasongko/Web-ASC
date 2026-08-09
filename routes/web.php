<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendance;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\ClassStudentController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DevelopmentController as AdminDevelopment;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\RecommendationController as AdminRecommendation;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistration;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\ERaportController;
use App\Http\Controllers\OrangTua\DashboardController as OrangTuaDashboard;
use App\Http\Controllers\OrangTua\RegistrationController as OrangTuaRegistration;
use App\Http\Controllers\Pelatih\AttendanceController as PelatihAttendance;
use App\Http\Controllers\Pelatih\DashboardController as PelatihDashboard;
use App\Http\Controllers\Pelatih\DevelopmentController as PelatihDevelopment;
use App\Http\Controllers\Pelatih\RecommendationController as PelatihRecommendation;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route dashboard & fitur khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('coaches', CoachController::class)->except(['show']);
    Route::patch('coaches/{coach}/toggle-active', [CoachController::class, 'toggleActive'])->name('coaches.toggle-active');
    Route::patch('coaches/{coach}/reset-password', [CoachController::class, 'resetPassword'])->name('coaches.reset-password');

    Route::resource('parents', ParentController::class)->except(['show']);
    Route::get('parents/{parent}', [ParentController::class, 'show'])->name('parents.show');
    Route::patch('parents/{parent}/toggle-active', [ParentController::class, 'toggleActive'])->name('parents.toggle-active');
    Route::patch('parents/{parent}/reset-password', [ParentController::class, 'resetPassword'])->name('parents.reset-password');

    // Route Registrasi untuk Admin
    Route::get('registrations', [AdminRegistration::class, 'index'])->name('registrations.index');
    Route::get('registrations/{registration}', [AdminRegistration::class, 'show'])->name('registrations.show');
    Route::patch('registrations/{registration}/accept', [AdminRegistration::class, 'accept'])->name('registrations.accept');
    Route::patch('registrations/{registration}/reject', [AdminRegistration::class, 'reject'])->name('registrations.reject');

    // Route Siswa untuk Admin
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');

    // Route Kelas, Jadwal, Penempatan Siswa
    Route::resource('classes', SchoolClassController::class);
    Route::post('classes/{class}/schedules', [ClassScheduleController::class, 'store'])->name('classes.schedules.store');
    Route::delete('schedules/{schedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('class-students/unplaced', [ClassStudentController::class, 'unplaced'])->name('class-students.unplaced');
    Route::post('registrations/{registration}/place', [ClassStudentController::class, 'place'])->name('class-students.place');
    Route::delete('classes/{class}/students/{studentId}', [ClassStudentController::class, 'remove'])->name('class-students.remove');
    Route::patch('class-students/{enrollment}/renew', [ClassStudentController::class, 'renew'])->name('class-students.renew');
    Route::patch('class-students/{enrollment}/stop', [ClassStudentController::class, 'stop'])->name('class-students.stop');
    Route::patch('class-students/{enrollment}/activate', [ClassStudentController::class, 'activate'])->name('class-students.activate');

    // Route Absensi untuk Admin
    Route::get('attendances', [AdminAttendance::class, 'index'])->name('attendances.index');
    Route::get('attendances/{class}/create', [AdminAttendance::class, 'create'])->name('attendances.create');
    Route::post('attendances/{class}', [AdminAttendance::class, 'store'])->name('attendances.store');
    Route::get('attendances/{class}/history', [AdminAttendance::class, 'history'])->name('attendances.history');
    Route::get('attendance-records/{attendance}/edit', [AdminAttendance::class, 'edit'])->name('attendances.edit');
    Route::put('attendance-records/{attendance}', [AdminAttendance::class, 'update'])->name('attendances.update');
    Route::delete('attendance-records/{attendance}', [AdminAttendance::class, 'destroy'])->name('attendances.destroy');

    // Route Perkembangan Siswa untuk Admin
    Route::get('developments', [AdminDevelopment::class, 'index'])->name('developments.index');
    Route::delete('developments/{development}', [AdminDevelopment::class, 'destroy'])->name('developments.destroy');
    Route::get('classes/{class}/developments', [AdminDevelopment::class, 'classIndex'])->name('classes.developments.index');
    Route::get('classes/{class}/developments/{student}/create', [AdminDevelopment::class, 'create'])->name('classes.developments.create');
    Route::post('classes/{class}/developments/{student}', [AdminDevelopment::class, 'store'])->name('classes.developments.store');
    Route::get('classes/{class}/developments/{student}/history', [AdminDevelopment::class, 'history'])->name('classes.developments.history');

    // Route Rekomendasi Naik Kelas untuk Admin
    Route::get('recommendations', [AdminRecommendation::class, 'index'])->name('recommendations.index');
    Route::post('recommendations', [AdminRecommendation::class, 'store'])->name('recommendations.store');
    Route::post('recommendations/{recommendation}/approve', [AdminRecommendation::class, 'approve'])->name('recommendations.approve');
    Route::post('recommendations/{recommendation}/reject', [AdminRecommendation::class, 'reject'])->name('recommendations.reject');
    Route::delete('recommendations/{recommendation}', [AdminRecommendation::class, 'destroy'])->name('recommendations.destroy');
});

Route::middleware(['auth', 'role:pelatih'])->prefix('pelatih')->name('pelatih.')->group(function () {
    Route::get('/dashboard', [PelatihDashboard::class, 'index'])->name('dashboard');

    // Route Absensi untuk Pelatih
    Route::get('attendances', [PelatihAttendance::class, 'index'])->name('attendances.index');
    Route::get('attendances/{class}/create', [PelatihAttendance::class, 'create'])->name('attendances.create');
    Route::post('attendances/{class}', [PelatihAttendance::class, 'store'])->name('attendances.store');
    Route::get('attendances/{class}/history', [PelatihAttendance::class, 'history'])->name('attendances.history');

    // Route Perkembangan Siswa untuk Pelatih
    Route::get('classes/{class}/developments', [PelatihDevelopment::class, 'index'])->name('developments.index');
    Route::get('classes/{class}/developments/{student}/create', [PelatihDevelopment::class, 'create'])->name('developments.create');
    Route::post('classes/{class}/developments/{student}', [PelatihDevelopment::class, 'store'])->name('developments.store');
    Route::get('classes/{class}/developments/{student}/history', [PelatihDevelopment::class, 'history'])->name('developments.history');

    // Route Rekomendasi Naik Kelas untuk Pelatih
    Route::post('classes/{class}/students/{student}/recommendations', [PelatihRecommendation::class, 'store'])->name('recommendations.store');
});

Route::middleware(['auth', 'role:orang_tua'])->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', [OrangTuaDashboard::class, 'index'])->name('dashboard');

    // Route Registrasi untuk Orang Tua
    Route::get('registrations', [OrangTuaRegistration::class, 'index'])->name('registrations.index');
    Route::get('registrations/create', [OrangTuaRegistration::class, 'create'])->name('registrations.create');
    Route::post('registrations', [OrangTuaRegistration::class, 'store'])->name('registrations.store');
});

// Router: arahkan /dashboard sesuai role user yang login
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'pelatih' => redirect()->route('pelatih.dashboard'),
        'orang_tua' => redirect()->route('orangtua.dashboard'),
        default => redirect('/login'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route E-Raport (dipakai bersama Admin & Orang Tua, otorisasi dicek di controller)
    Route::get('/eraport/{student}/{developmentId}', [ERaportController::class, 'show'])->name('eraport.show');
    Route::get('/eraport/{student}/{developmentId}/pdf', [ERaportController::class, 'downloadPdf'])->name('eraport.pdf');
});

require __DIR__.'/auth.php';
