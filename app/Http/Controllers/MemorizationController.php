<?php

namespace App\Http\Controllers;

use App\Models\Memorization;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemorizationController extends Controller
{
    public function index()
    {
        return Inertia::render('Tahfidz/Index', [
            'recent_memorizations' => Memorization::with('student', 'teacher')->latest()->paginate(20),
            'students' => Student::select('id', 'name', 'nis')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'surah_name' => 'required|string',
            'surah_number' => 'nullable|integer',
            'juz_number' => 'nullable|integer',
            'ayat_start' => 'nullable|integer',
            'ayat_end' => 'nullable|integer',
            'type' => 'required|in:setoran_baru,murojaah',
            'status' => 'required|in:lancar,kurang_lancar,ulang',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['teacher_id'] = auth()->id();
        $validated['recorded_at'] = now();

        Memorization::create($validated);

        // Auto-log WhatsApp notification (Mocked)
        $student = Student::find($validated['student_id']);
        if ($student->phone_number) {
            \App\Services\WhatsappService::send($student->phone_number, \App\Services\WhatsappService::notifyTahfidz($student->name, $validated['surah_name'], $validated['status']));
        }

        return redirect()->back()->with('success', 'Setoran tahfidz berhasil dicatat dan notifikasi wali santri telah dilog.');
    }
}
