<?php

namespace App\Http\Controllers;

use App\Models\StudentBill;
use App\Models\Student;
use App\Models\BillCategory;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentBillController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentBill::with(['student', 'category']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        return Inertia::render('Finance/Bills/Index', [
            'bills' => $query->latest()->paginate(15)->withQueryString(),
            'students' => Student::all(['id', 'name']),
            'categories' => BillCategory::all(['id', 'name', 'default_amount']),
            'classrooms' => Classroom::all(['id', 'name']),
            'filters' => $request->only(['status', 'student_id']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:bill_categories,id',
            'type' => 'required|in:individual,classroom',
            'student_id' => 'required_if:type,individual|exists:students,id',
            'classroom_id' => 'required_if:type,classroom|exists:classrooms,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'period_month' => 'nullable|string', // YYYY-MM
        ]);

        $category = BillCategory::find($request->category_id);
        $studentIds = [];

        if ($request->type === 'individual') {
            $studentIds[] = $request->student_id;
        } else {
            $studentIds = Student::where('classroom_id', $request->classroom_id)->pluck('id')->toArray();
        }

        foreach ($studentIds as $id) {
            StudentBill::create([
                'tenant_id' => auth()->user()->tenant_id,
                'student_id' => $id,
                'bill_category_id' => $category->id,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'period_month' => $request->period_month,
                'status' => 'unpaid',
            ]);
        }

        return redirect()->back()->with('success', 'Tagihan berhasil dibuat untuk ' . count($studentIds) . ' santri.');
    }

    public function destroy(StudentBill $bill)
    {
        if ($bill->paid_amount > 0) {
            return redirect()->back()->with('error', 'Tagihan yang sudah dibayar sebagian tidak dapat dihapus.');
        }
        $bill->delete();
        return redirect()->back()->with('success', 'Tagihan berhasil dihapus.');
    }
}
