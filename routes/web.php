<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DormitoryController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Finance\MerchantController;
use App\Http\Controllers\StudentBillController;
use App\Http\Controllers\BillCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TahfidzController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\WhatsappLogController;
use App\Http\Controllers\WaliController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\SystemHealthController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard & Reports
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Merchant POS
    Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant.index');
    Route::post('/merchant/purchase', [MerchantController::class, 'purchase'])->name('merchant.purchase');

    // Academic & Dormitory Modules
    Route::resource('students', StudentController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('subjects', SubjectController::class);
    // Route::resource('dormitories', DormitoryController::class); // Replaced by more specific dormitory-rooms route
    Route::resource('schedules', ScheduleController::class);
    Route::resource('attendance', AttendanceController::class);

    // Dormitories (Improved Routing)
    Route::get('/dormitory-rooms', [DormitoryController::class, 'index'])->name('dormitory-rooms.index');
    Route::post('/dormitories', [DormitoryController::class, 'store'])->name('dormitories.store');
    Route::put('/dormitories/{dormitory}', [DormitoryController::class, 'update'])->name('dormitories.update');
    Route::delete('/dormitories/{dormitory}', [DormitoryController::class, 'destroy'])->name('dormitories.destroy');
    Route::post('/dormitories/assign', [DormitoryController::class, 'assignStudent'])->name('dormitories.assign');

    // Finance & Bills
    Route::resource('student-bills', StudentBillController::class);
    Route::post('student-bills/{bill}/pay', [StudentBillController::class, 'pay'])->name('student-bills.pay');
    Route::resource('bill-categories', BillCategoryController::class);

    // Financial History (Views)
    Route::get('/transactions', function () {
        return Inertia::render('Finance/Transactions/Index');
    })->name('transactions.index');
    Route::get('/wallets', function () {
        return Inertia::render('Finance/Wallets/Index');
    })->name('wallets.index');

    Route::get('/violations', [ViolationController::class, 'index'])->name('violations.index');
    Route::post('/violations', [ViolationController::class, 'store'])->name('violations.store');
    Route::put('/violations/{violation}', [ViolationController::class, 'update'])->name('violations.update');
    Route::delete('/violations/{violation}', [ViolationController::class, 'destroy'])->name('violations.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('/tahfidz', [TahfidzController::class, 'index'])->name('tahfidz.index');
    Route::post('/tahfidz', [TahfidzController::class, 'store'])->name('tahfidz.store');
    Route::delete('/tahfidz/{tahfidz}', [TahfidzController::class, 'destroy'])->name('tahfidz.destroy');
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::post('/exams', [GradeController::class, 'storeExam'])->name('exams.store');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('teachers', TeacherController::class);
    Route::get('/teacher-attendance', [TeacherAttendanceController::class, 'index'])->name('teacher-attendance.index');
    Route::post('/teacher-attendance', [TeacherAttendanceController::class, 'store'])->name('teacher-attendance.store');
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::post('/payrolls/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
    Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
    Route::get('/whatsapp-logs', [WhatsappLogController::class, 'index'])->name('whatsapp-logs.index');

    // Super Admin Routes
    Route::prefix('super-admin')->name('super-admin.')->group(function () {
        Route::resource('tenants', TenantController::class);
        Route::get('/health', [SystemHealthController::class, 'index'])->name('health');
    });
});
