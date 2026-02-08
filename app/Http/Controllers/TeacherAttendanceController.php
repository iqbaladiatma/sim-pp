<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: date('Y-m-d');

        $teachers = Teacher::with([
            'user',
            'attendances' => function ($query) use ($date) {
                $query->where('date', $date);
            }
        ])->get()->map(function ($teacher) {
            $attendance = $teacher->attendances->first();
            $teacher->attendance_status = $attendance ? $attendance->status : 'hadir'; // Default to 'hadir' for ease
            $teacher->check_in = $attendance ? $attendance->check_in : null;
            $teacher->check_out = $attendance ? $attendance->check_out : null;
            $teacher->attendance_id = $attendance ? $attendance->id : null;
            return $teacher;
        });

        return Inertia::render('SDM/Attendance/Index', [
            'teachers' => $teachers,
            'filters' => ['date' => $date],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.teacher_id' => 'required|exists:teachers,id',
            'attendance.*.status' => 'required|in:hadir,sakit,izin,alpha',
            'attendance.*.check_in' => 'nullable|date_format:H:i',
            'attendance.*.check_out' => 'nullable|date_format:H:i',
        ]);

        foreach ($request->attendance as $item) {
            TeacherAttendance::updateOrCreate(
                [
                    'teacher_id' => $item['teacher_id'],
                    'date' => $request->date,
                    'tenant_id' => auth()->user()->tenant_id,
                ],
                [
                    'status' => $item['status'],
                    'check_in' => $item['check_in'] ?? null,
                    'check_out' => $item['check_out'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Presensi guru berhasil disimpan.');
    }
}
