<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController extends Controller
{
    public function index()
    {
        return Inertia::render('Classrooms/Index', [
            'classrooms' => Classroom::with('homeroomTeacher')->latest()->get(),
            'teachers' => User::whereIn('role', ['ustadz', 'admin_pondok'])->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Classroom::create($validated);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $classroom->update($validated);

        return redirect()->back()->with('success', 'Data kelas diperbarui.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus.');
    }
}
