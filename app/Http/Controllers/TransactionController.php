<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\StudentBill;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Finance/Transactions/Index', [
            'transactions' => Transaction::with(['bill.student', 'bill.category', 'recordedBy'])->latest()->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_bill_id' => 'required|exists:student_bills,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $bill = StudentBill::findOrFail($request->student_bill_id);

        if ($request->amount > ($bill->amount - $bill->paid_amount)) {
            return redirect()->back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.');
        }

        DB::transaction(function () use ($request, $bill) {
            Transaction::create([
                'tenant_id' => auth()->user()->tenant_id,
                'student_bill_id' => $bill->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'status' => 'completed',
                'recorded_by' => auth()->id(),
                'note' => $request->note,
            ]);

            $bill->paid_amount += $request->amount;

            if ($bill->paid_amount >= $bill->amount) {
                $bill->status = 'paid';
            } else {
                $bill->status = 'partially_paid';
            }

            $bill->save();
        });

        return redirect()->back()->with('success', 'Pembayaran berhasil dicatat.');
    }
}
