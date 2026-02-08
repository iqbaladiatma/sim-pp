<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?: Carbon::today()->toDateString();

        $teachers = Teacher::with([
            'user',
            'attendances' => function ($q) use ($date) {
                $q->where('date', $date);
            }
        ])->get();

        return Inertia::render('HR/Attendance/Index', [
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
        ]);

        foreach ($request->attendance as $att) {
            TeacherAttendance::updateOrCreate(
                [
                    'tenant_id' => auth()->user()->tenant_id,
                    'teacher_id' => $att['teacher_id'],
                    'date' => $request->date,
                ],
                [
                    'status' => $att['status'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Presensi ustadz berhasil disimpan.');
    }
}
