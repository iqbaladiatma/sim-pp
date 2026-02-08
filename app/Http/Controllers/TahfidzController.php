<?php

namespace App\Http\Controllers;

use App\Models\TahfidzProgress;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TahfidzController extends Controller
{
    public function index(Request $request)
    {
        $query = TahfidzProgress::with(['student', 'recordedBy']);

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        return Inertia::render('Academic/Tahfidz/Index', [
            'progress' => $query->latest()->paginate(15),
            'students' => Student::all(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'surah_name' => 'required|string|max:255',
            'start_verse' => 'nullable|integer',
            'end_verse' => 'nullable|integer',
            'type' => 'required|in:ziyadah,murajaah',
            'quality' => 'required|in:A,B,C,D',
            'recorded_at' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['recorded_by'] = auth()->id();

        TahfidzProgress::create($validated);

        return redirect()->back()->with('success', 'Progres tahfidz berhasil dicatat.');
    }

    public function destroy(TahfidzProgress $tahfidz)
    {
        $tahfidz->delete();
        return redirect()->back()->with('success', 'Catatan tahfidz dihapus.');
    }
}
