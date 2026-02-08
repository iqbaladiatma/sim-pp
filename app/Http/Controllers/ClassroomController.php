<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Classroom::with('homeroomTeacher');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Academic/Classrooms/Index', [
            'classrooms' => $query->latest()->paginate(10)->withQueryString(),
            'teachers' => User::where('role', 'ustadz')->orWhere('role', 'admin_pondok')->get(['id', 'name']),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
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
            'level' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $classroom->update($validated);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus.');
    }
}
