<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Pelatih\DashboardController as PelatihDashboard;
use App\Http\Controllers\OrangTua\DashboardController as OrangTuaDashboard;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\OrangTua\RegistrationController as OrangTuaRegistration;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistration;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\ClassStudentController;
use App\Http\Controllers\Pelatih\AttendanceController as PelatihAttendance;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendance;
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
    Route::patch('parents/{parent}/reset-password', [ParentController::class, 'resetPassword'])->name('parents.reset-password');


    // Route Registrasi untuk Admin
    Route::get('registrations', [AdminRegistration::class, 'index'])->name('registrations.index');
    Route::get('registrations/{registration}', [AdminRegistration::class, 'show'])->name('registrations.show');
    Route::patch('registrations/{registration}/accept', [AdminRegistration::class, 'accept'])->name('registrations.accept');
    Route::patch('registrations/{registration}/reject', [AdminRegistration::class, 'reject'])->name('registrations.reject');

    // Route Kelas, Jadwal, Penempatan Siswa
    Route::resource('classes', SchoolClassController::class);
    Route::post('classes/{class}/schedules', [ClassScheduleController::class, 'store'])->name('classes.schedules.store');
    Route::delete('schedules/{schedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('class-students/unplaced', [ClassStudentController::class, 'unplaced'])->name('class-students.unplaced');
    Route::post('registrations/{registration}/place', [ClassStudentController::class, 'place'])->name('class-students.place');
    Route::delete('classes/{class}/students/{studentId}', [ClassStudentController::class, 'remove'])->name('class-students.remove');

    // Route Absensi untuk Admin
    Route::get('attendances', [AdminAttendance::class, 'index'])->name('attendances.index');
    Route::get('attendances/{class}/create', [AdminAttendance::class, 'create'])->name('attendances.create');
    Route::post('attendances/{class}', [AdminAttendance::class, 'store'])->name('attendances.store');
    Route::get('attendances/{class}/history', [AdminAttendance::class, 'history'])->name('attendances.history');
    Route::get('attendance-records/{attendance}/edit', [AdminAttendance::class, 'edit'])->name('attendances.edit');
    Route::put('attendance-records/{attendance}', [AdminAttendance::class, 'update'])->name('attendances.update');
    Route::delete('attendance-records/{attendance}', [AdminAttendance::class, 'destroy'])->name('attendances.destroy');
});


Route::middleware(['auth', 'role:pelatih'])->prefix('pelatih')->name('pelatih.')->group(function () {
    Route::get('/dashboard', [PelatihDashboard::class, 'index'])->name('dashboard');

    // Route Absensi untuk Pelatih
    Route::get('attendances', [PelatihAttendance::class, 'index'])->name('attendances.index');
    Route::get('attendances/{class}/create', [PelatihAttendance::class, 'create'])->name('attendances.create');
    Route::post('attendances/{class}', [PelatihAttendance::class, 'store'])->name('attendances.store');
    Route::get('attendances/{class}/history', [PelatihAttendance::class, 'history'])->name('attendances.history');
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
});


require __DIR__.'/auth.php';
