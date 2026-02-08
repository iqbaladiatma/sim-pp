<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Exam;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $examId = $request->exam_id;
        $classroomId = $request->classroom_id;
        $subjectId = $request->subject_id;

        $students = [];

        if ($classroomId && $subjectId && $examId) {
            $students = Student::where('classroom_id', $classroomId)
                ->with([
                    'grades' => function ($query) use ($examId, $subjectId) {
                        $query->where('exam_id', $examId)
                            ->where('subject_id', $subjectId);
                    }
                ])
                ->get()
                ->map(function ($student) {
                    $student->grade_score = $student->grades->first() ? $student->grades->first()->score : null;
                    return $student;
                });
        }

        return Inertia::render('Academic/Grades/Index', [
            'exams' => Exam::latest()->get(),
            'classrooms' => Classroom::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name', 'code']),
            'students' => $students,
            'filters' => $request->only(['exam_id', 'classroom_id', 'subject_id']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->grades as $item) {
                Grade::updateOrCreate(
                    [
                        'tenant_id' => auth()->user()->tenant_id,
                        'student_id' => $item['student_id'],
                        'exam_id' => $request->exam_id,
                        'subject_id' => $request->subject_id,
                    ],
                    [
                        'score' => $item['score'],
                        // 'note' => $item['note'] ?? null, // Optional note
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }

    // Additional methods for Exam management could be added here or in a separate ExamController
    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', // e.g., "PTS Ganjil 2024/2025"
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:1,2',
            'is_active' => 'boolean'
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        if ($validated['is_active']) {
            // Deactivate other exams if this one is active (optional logic)
            Exam::where('tenant_id', auth()->user()->tenant_id)->update(['is_active' => false]);
        }

        Exam::create($validated);

        return redirect()->back()->with('success', 'Data ujian berhasil dibuat.');
    }
}
