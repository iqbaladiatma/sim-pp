<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: date('Y-m-d');
        $classroomId = $request->classroom_id;

        $students = [];
        if ($classroomId) {
            $students = Student::where('classroom_id', $classroomId)
                ->with([
                    'attendances' => function ($query) use ($date) {
                        $query->where('date', $date);
                    }
                ])->get();
        }

        return Inertia::render('Academic/Attendance/Index', [
            'classrooms' => Classroom::all(['id', 'name']),
            'students' => $students,
            'filters' => [
                'date' => $date,
                'classroom_id' => $classroomId,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:h,s,i,a',
        ]);

        foreach ($request->attendance as $item) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $item['student_id'],
                    'date' => $request->date,
                    'tenant_id' => auth()->user()->tenant_id,
                ],
                [
                    'status' => $item['status'],
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Presensi berhasil disimpan.');
    }
}
