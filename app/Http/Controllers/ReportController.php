<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use App\Models\StudentBill;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly income (Transactions) vs Expenses
        $incomeByMonth = Transaction::select(
            DB::raw('sum(amount) as total'),
            DB::raw("MONTH(payment_date) as month")
        )
            ->whereYear('payment_date', date('Y'))
            ->groupBy('month')
            ->get();

        $expenseByMonth = Expense::select(
            DB::raw('sum(amount) as total'),
            DB::raw("MONTH(expense_date) as month")
        )
            ->whereYear('expense_date', date('Y'))
            ->groupBy('month')
            ->get();

        // Outstanding bills by type
        $outstandingByType = StudentBill::with('category')
            ->select('bill_category_id', DB::raw('sum(amount - paid_amount) as total'))
            ->where('status', '!=', 'paid')
            ->groupBy('bill_category_id')
            ->get();

        return Inertia::render('Reports/Index', [
            'incomeByMonth' => $incomeByMonth,
            'expenseByMonth' => $expenseByMonth,
            'outstandingByType' => $outstandingByType,
        ]);
    }
}
