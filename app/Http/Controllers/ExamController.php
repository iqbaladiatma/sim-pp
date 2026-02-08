<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    public function index()
    {
        return Inertia::render('Academic/Exams/Index', [
            'exams' => Exam::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'is_active' => 'boolean',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Exam::create($validated);

        return redirect()->back()->with('success', 'Ujian/Semester berhasil ditambahkan.');
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'is_active' => 'boolean',
        ]);

        $exam->update($validated);

        return redirect()->back()->with('success', 'Data ujian berhasil diperbarui.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->back()->with('success', 'Ujian berhasil dihapus.');
    }
}
