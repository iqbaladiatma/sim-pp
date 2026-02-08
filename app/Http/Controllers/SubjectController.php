<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Academic/Subjects/Index', [
            'subjects' => Subject::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        Subject::create($validated);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        return redirect()->back()->with('success', 'Mata pelajaran diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->back()->with('success', 'Mata pelajaran dihapus.');
    }
}
