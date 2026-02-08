<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['classroom', 'subject', 'teacher']);

        if ($request->classroom_id) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->day) {
            $query->where('day_of_week', $request->day);
        }

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        return Inertia::render('Academic/Schedules/Index', [
            'schedules' => $query->orderByRaw("FIELD(day_of_week, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")->orderBy('start_time')->get(),
            'classrooms' => Classroom::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name', 'code']),
            'teachers' => User::where('role', 'ustadz')->get(['id', 'name']),
            'filters' => $request->only(['classroom_id', 'day', 'teacher_id']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Schedule::create($validated);

        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule->update($validated);

        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
