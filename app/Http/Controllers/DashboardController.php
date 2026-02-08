<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\Attendance;
use App\Models\StudentBill;
use App\Models\Transaction;
use App\Models\Violation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isWaliSantri()) {
            return (new WaliController())->index();
        }

        $tenantId = $user->tenant_id;

        // 1. Student Statistics
        $totalStudents = Student::count();
        $maleStudents = Student::where('gender', 'L')->count();
        $femaleStudents = Student::where('gender', 'P')->count();

        // 2. Attendance Today
        $attendanceToday = Attendance::where('date', Carbon::today()->toDateString())->get();
        $presentToday = $attendanceToday->where('status', 'h')->count();

        // 3. Finance Summary
        $totalBills = StudentBill::sum('amount');
        $totalPaid = StudentBill::sum('paid_amount');
        $totalOutstanding = $totalBills - $totalPaid;

        $collectability = $totalBills > 0 ? round(($totalPaid / $totalBills) * 100, 1) : 0;

        // 4. Recent Transactions
        $recentTransactions = Transaction::with(['bill.student', 'bill.category'])
            ->latest()
            ->limit(5)
            ->get();

        // 5. Recent Violations
        $recentViolations = Violation::with('student')
            ->latest()
            ->limit(5)
            ->get();

        // 6. Classroom Distribution (Short summary)
        $classrooms = Classroom::withCount('students')->get();

        return Inertia::render('Home', [
            'stats' => [
                'students' => [
                    'total' => $totalStudents,
                    'male' => $maleStudents,
                    'female' => $femaleStudents,
                ],
                'attendance' => [
                    'present' => $presentToday,
                ],
                'finance' => [
                    'total_bills' => $totalBills,
                    'total_paid' => $totalPaid,
                    'total_outstanding' => $totalOutstanding,
                    'collectability' => $collectability,
                    'total_balance' => \App\Models\StudentWallet::sum('balance'),
                    'monthly_expense' => \App\Models\Expense::whereMonth('expense_date', now()->month)->sum('amount'),
                ],
                'hr' => [
                    'total_teachers' => \App\Models\Teacher::count(),
                    'teacher_present' => \App\Models\TeacherAttendance::where('date', now()->toDateString())->where('status', 'hadir')->count(),
                ],
                'academic' => [
                    'tahfidz_today' => \App\Models\Memorization::whereDate('recorded_at', now()->toDateString())->count(),
                ]
            ],
            'recent' => [
                'transactions' => $recentTransactions,
                'violations' => $recentViolations,
            ],
            'classrooms' => $classrooms,
        ]);
    }
}
