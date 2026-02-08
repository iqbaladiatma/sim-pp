<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['ustadz', 'admin_pondok']);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('SDM/Teachers/Index', [
            'teachers' => $query->latest()->paginate(12)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:ustadz,admin_pondok',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['tenant_id'] = auth()->user()->tenant_id;

        User::create($validated);

        return redirect()->back()->with('success', 'Data ustadz/pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:ustadz,admin_pondok',
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $teacher->update($validated);

        return redirect()->back()->with('success', 'Data ustadz/pegawai berhasil diperbarui.');
    }

    public function destroy(User $teacher)
    {
        // Prevent deleting self
        if ($teacher->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $teacher->delete();

        return redirect()->back()->with('success', 'Data ustadz/pegawai berhasil dihapus.');
    }
}
