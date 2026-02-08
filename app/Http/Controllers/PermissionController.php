<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with('student');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return Inertia::render('Academic/Permissions/Index', [
            'permissions' => $query->latest()->paginate(10)->withQueryString(),
            'students' => Student::all(['id', 'name']),
            'filters' => $request->only(['status']),
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

        return redirect()->back()->with('success', 'Permohonan izin berhasil dibuat.');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,returned',
            'note' => 'nullable|string',
        ]);

        if ($validated['status'] == 'approved') {
            $validated['approved_by'] = auth()->id();
        }

        if ($validated['status'] == 'returned') {
            $validated['actual_return_date'] = now();
        }

        $permission->update($validated);

        return redirect()->back()->with('success', 'Status izin berhasil diperbarui.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->back()->with('success', 'Data izin berhasil dihapus.');
    }
}
