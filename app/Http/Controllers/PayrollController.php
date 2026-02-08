<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?: date('m');
        $year = $request->year ?: date('Y');

        $query = Payroll::with('teacher.user')
            ->where('month', $month)
            ->where('year', $year);

        return Inertia::render('Finance/Payrolls/Index', [
            'payrolls' => $query->latest()->get(),
            'filters' => ['month' => $month, 'year' => $year],
            'teachers_count' => Teacher::count(),
            'total_salary' => $query->sum('total_salary'),
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024',
        ]);

        $exists = Payroll::where('month', $request->month)
            ->where('year', $request->year)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Gaji untuk periode ini sudah digenerate.');
        }

        DB::transaction(function () use ($request) {
            $teachers = Teacher::all();
            foreach ($teachers as $teacher) {
                // Calculate simple salary logic (expandable)
                // Base salary from teacher profile
                // Allowances could be dynamic based on attendance (future feature)

                $baseSalary = $teacher->salary;
                $allowances = 0; // Placeholder
                $deductions = 0; // Placeholder

                Payroll::create([
                    'tenant_id' => auth()->user()->tenant_id,
                    'teacher_id' => $teacher->id,
                    'month' => $request->month,
                    'year' => $request->year,
                    'base_salary' => $baseSalary,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'total_salary' => $baseSalary + $allowances - $deductions,
                    'status' => 'pending'
                ]);
            }
        });

        return redirect()->back()->with('success', 'Payroll berhasil digenerate.');
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'allowances' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $validated['total_salary'] = $validated['base_salary'] + $validated['allowances'] - $validated['deductions'];

        if ($validated['status'] === 'paid' && empty($validated['payment_date'])) {
            $validated['payment_date'] = now();
        }

        $payroll->update($validated);

        return redirect()->back()->with('success', 'Data gaji diperbarui.');
    }
}
