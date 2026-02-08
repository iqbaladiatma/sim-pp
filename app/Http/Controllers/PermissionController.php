<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('Permissions/Index', [
            'permissions' => Permission::with(['student', 'approvedBy'])->latest()->paginate(10),
            'students' => Student::all(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:sakit,pulang,izin_khusus',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['status'] = 'pending';

        Permission::create($validated);

        return redirect()->back()->with('success', 'Permohonan izin diajukan.');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,on_leave,returned',
            'note' => 'nullable|string',
            'actual_return_date' => 'nullable|date',
        ]);

        if (in_array($validated['status'], ['approved', 'rejected'])) {
            $validated['approved_by'] = auth()->id();
        }

        $permission->update($validated);

        return redirect()->back()->with('success', 'Status perizinan diperbarui.');
    }
}
