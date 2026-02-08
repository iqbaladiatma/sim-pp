<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Violation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::with(['student', 'recorder']);

        if ($request->search) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return Inertia::render('Academic/Violations/Index', [
            'violations' => $query->latest('incident_date')->paginate(10)->withQueryString(),
            'students' => Student::all(['id', 'name']),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|string',
            'incident_date' => 'required|date',
            'points_deduction' => 'required|integer|min:0',
            'description' => 'required|string',
            'action_taken' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['recorded_by'] = auth()->id();

        Violation::create($validated);

        return redirect()->back()->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function update(Request $request, Violation $violation)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|string',
            'incident_date' => 'required|date',
            'points_deduction' => 'required|integer|min:0',
            'description' => 'required|string',
            'action_taken' => 'nullable|string',
        ]);

        $violation->update($validated);

        return redirect()->back()->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    public function destroy(Violation $violation)
    {
        $violation->delete();

        return redirect()->back()->with('success', 'Data pelanggaran berhasil dihapus.');
    }
}
