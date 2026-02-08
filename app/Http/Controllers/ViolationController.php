<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViolationController extends Controller
{
    public function index()
    {
        return Inertia::render('Violations/Index', [
            'violations' => Violation::with(['student', 'recordedBy'])->latest()->paginate(10),
            'students' => Student::all(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|string',
            'description' => 'required|string',
            'points_deduction' => 'required|integer|min:0',
            'incident_date' => 'required|date',
            'action_taken' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['recorded_by'] = auth()->id();

        $violation = Violation::create($validated);

        // Deduct points from student
        $student = Student::find($validated['student_id']);
        $student->decrement('discipline_points', $validated['points_deduction']);

        return redirect()->back()->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function destroy(Violation $violation)
    {
        // Restore points before deleting
        $student = $violation->student;
        $student->increment('discipline_points', $violation->points_deduction);

        $violation->delete();
        return redirect()->back()->with('success', 'Catatan pelanggaran dihapus dan poin dikembalikan.');
    }
}
