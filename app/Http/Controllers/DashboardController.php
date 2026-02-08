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
        $tenantId = auth()->user()->tenant_id;

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
                ],
            ],
            'recent' => [
                'transactions' => $recentTransactions,
                'violations' => $recentViolations,
            ],
            'classrooms' => $classrooms,
        ]);
    }
}
