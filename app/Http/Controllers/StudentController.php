<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\DormitoryRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['classroom', 'dormitoryRoom']);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('nis', 'like', '%' . $request->search . '%');
        }

        if ($request->classroom_id) {
            $query->where('classroom_id', $request->classroom_id);
        }

        return Inertia::render('Students/Index', [
            'students' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only(['search', 'classroom_id']),
            'classrooms' => Classroom::all(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Students/Create', [
            'classrooms' => Classroom::all(['id', 'name']),
            'dormitoryRooms' => DormitoryRoom::all(['id', 'building_name', 'room_number']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis',
            'nisn' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'dormitory_room_id' => 'nullable|exists:dormitory_rooms,id',
            'entry_date' => 'nullable|date',
            'status' => 'required|in:active,alumni,dropped_out,transferred',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return Inertia::render('Students/Show', [
            'student' => $student->load(['classroom', 'dormitoryRoom', 'parent']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student,
            'classrooms' => Classroom::all(['id', 'name']),
            'dormitoryRooms' => DormitoryRoom::all(['id', 'building_name', 'room_number']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis,' . $student->id,
            'nisn' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'dormitory_room_id' => 'nullable|exists:dormitory_rooms,id',
            'entry_date' => 'nullable|date',
            'status' => 'required|in:active,alumni,dropped_out,transferred',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data santri berhasil dihapus.');
    }
}
