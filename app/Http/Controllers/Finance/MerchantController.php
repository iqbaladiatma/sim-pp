<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $students = [];
        if ($request->search) {
            $students = Student::with('wallet')
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('nis', 'like', "%{$request->search}%")
                ->orWhereHas('wallet', function ($q) use ($request) {
                    $q->where('card_number', $request->search);
                })
                ->limit(5)
                ->get();
        }

        $recentTransactions = WalletTransaction::with('wallet.student')
            ->where('type', 'purchase')
            ->where('merchant_name', 'Default Merchant') // Bisa dikembangkan per user merchant
            ->latest()
            ->limit(10)
            ->get();

        return Inertia::render('Finance/Wallet/Merchant', [
            'students' => $students,
            'recentTransactions' => $recentTransactions,
            'filters' => $request->only(['search']),
        ]);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $student = Student::findOrFail($request->student_id);
        $wallet = $student->wallet;

        if (!$wallet || $wallet->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Saldo tidak mencukupi atau dompet tidak aktif.']);
        }

        try {
            $wallet->deduct(
                $request->amount,
                $request->description ?: 'Pembelian di Kantin',
                'purchase'
            );
            return redirect()->back()->with('success', 'Transaksi berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['amount' => $e->getMessage()]);
        }
    }
}
