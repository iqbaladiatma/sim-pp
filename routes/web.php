<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Root dashboard redirection based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }
        return app(\App\Http\Controllers\DashboardController::class)->index();
    })->name('dashboard');

    // Super Admin Routes
    Route::middleware(['role:super_admin'])->prefix('super-admin')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Home', [
                'title' => 'Super Admin Dashboard'
            ]);
        })->name('super-admin.dashboard');
    });

    // Tenant Admin / Ustadz Routes
    Route::middleware(['role:admin_pondok,ustadz'])->group(function () {
        // Kesantrian Module
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('classrooms', \App\Http\Controllers\ClassroomController::class);
        Route::resource('dormitory-rooms', \App\Http\Controllers\DormitoryRoomController::class);
        Route::resource('violations', \App\Http\Controllers\ViolationController::class);
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class);

        // Academic Module
        Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
        Route::get('tahfidz', [\App\Http\Controllers\TahfidzController::class, 'index'])->name('tahfidz.index');
        Route::post('tahfidz', [\App\Http\Controllers\TahfidzController::class, 'store'])->name('tahfidz.store');
        Route::delete('tahfidz/{tahfidz}', [\App\Http\Controllers\TahfidzController::class, 'destroy'])->name('tahfidz.destroy');

        Route::get('attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');

        // Finance Module
        Route::resource('bill-categories', \App\Http\Controllers\BillCategoryController::class);
        Route::resource('student-bills', \App\Http\Controllers\StudentBillController::class);
        Route::resource('transactions', \App\Http\Controllers\TransactionController::class);
    });
});
