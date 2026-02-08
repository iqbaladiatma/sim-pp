<?php

namespace App\Http\Controllers;

use App\Models\DormitoryRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DormitoryController extends Controller
{
    public function index()
    {
        $rooms = DormitoryRoom::with(['supervisor', 'students'])
            ->withCount('students')
            ->get();

        $supervisors = User::whereIn('role', ['ustadz', 'staff'])->get();
        $unassignedStudents = Student::whereNull('dormitory_room_id')->get();

        return Inertia::render('Academic/Dormitory/Index', [
            'rooms' => $rooms,
            'supervisors' => $supervisors,
            'unassignedStudents' => $unassignedStudents,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'gender' => 'required|in:L,P',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        DormitoryRoom::create($validated);

        return redirect()->back()->with('success', 'Kamar asrama berhasil ditambahkan.');
    }

    public function update(Request $request, DormitoryRoom $dormitory)
    {
        $validated = $request->validate([
            'building_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',
            'floor' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'gender' => 'required|in:L,P',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $dormitory->update($validated);

        return redirect()->back()->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function assignStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'dormitory_room_id' => 'nullable|exists:dormitory_rooms,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $student->update(['dormitory_room_id' => $request->dormitory_room_id]);

        return redirect()->back()->with('success', 'Penempatan santri berhasil diperbarui.');
    }

    public function destroy(DormitoryRoom $dormitory)
    {
        if ($dormitory->students()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Kamar tidak dapat dihapus karena masih ada santri di dalamnya.']);
        }

        $dormitory->delete();
        return redirect()->back()->with('success', 'Kamar asrama berhasil dihapus.');
    }
}
