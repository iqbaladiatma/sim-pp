<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\StudentBill;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WaliController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Find students associated with this parent (wali_santri)
        $student = Student::where('parent_id', $user->id)
            ->with(['classroom', 'dormitoryRoom', 'wallet'])
            ->first();

        if (!$student) {
            return Inertia::render('Wali/NoStudent');
        }

        // Fetch recent attendance (last 10 records)
        $attendance = Attendance::where('student_id', $student->id)
            ->latest('date')
            ->limit(10)
            ->get()
            ->reverse()
            ->values();

        // Fetch pending bills
        $bills = StudentBill::with('category')
            ->where('student_id', $student->id)
            ->where('status', '!=', 'paid')
            ->get();

        // Fetch recent grades
        $grades = Grade::with(['subject', 'exam'])
            ->where('student_id', $student->id)
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Wali/Monitoring', [
            'student' => $student,
            'attendance' => $attendance,
            'bills' => $bills,
            'grades' => $grades,
        ]);
    }
}
