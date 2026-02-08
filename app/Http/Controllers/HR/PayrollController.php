<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?: now()->month;
        $year = $request->year ?: now()->year;

        $payrolls = Payroll::with('teacher.user')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $teachers = Teacher::with('user')->where('status', 'active')->get();

        return Inertia::render('HR/Payroll/Index', [
            'payrolls' => $payrolls,
            'teachers' => $teachers,
            'filters' => [
                'month' => (int) $month,
                'year' => (int) $year,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'base_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['total_salary'] = $validated['base_salary'] + ($validated['allowances'] ?? 0) - ($validated['deductions'] ?? 0);
        $validated['status'] = 'pending';

        Payroll::updateOrCreate(
            [
                'tenant_id' => $validated['tenant_id'],
                'teacher_id' => $validated['teacher_id'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Payroll berhasil diproses.');
    }

    public function updateStatus(Request $request, Payroll $payroll)
    {
        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Gaji telah ditandai sebagai Terbayar.');
    }
}
