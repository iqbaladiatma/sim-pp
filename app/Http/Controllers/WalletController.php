<?php

namespace App\Http\Controllers;

use App\Models\StudentWallet;
use App\Models\WalletTransaction;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        return Inertia::render('Finance/Wallet/Index', [
            'wallets' => StudentWallet::with('student')->get(),
        ]);
    }

    public function show(StudentWallet $wallet)
    {
        return Inertia::render('Finance/Wallet/Show', [
            'wallet' => $wallet->load([
                'student',
                'transactions' => function ($q) {
                    $q->latest()->limit(50);
                }
            ]),
        ]);
    }

    public function topup(Request $request, StudentWallet $wallet)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $wallet) {
            $balanceBefore = $wallet->balance;
            $wallet->increment('balance', $request->amount);

            WalletTransaction::create([
                'tenant_id' => $wallet->tenant_id,
                'wallet_id' => $wallet->id,
                'type' => 'topup',
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'description' => $request->note ?? 'Top up saldo',
            ]);
        });

        return redirect()->back()->with('success', 'Top up berhasil.');
    }

    public function purchase(Request $request, StudentWallet $wallet)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'merchant_name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($wallet->balance < $request->amount) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi.');
        }

        DB::transaction(function () use ($request, $wallet) {
            $balanceBefore = $wallet->balance;
            $wallet->decrement('balance', $request->amount);

            WalletTransaction::create([
                'tenant_id' => $wallet->tenant_id,
                'wallet_id' => $wallet->id,
                'type' => 'purchase',
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'merchant_name' => $request->merchant_name,
                'description' => $request->description ?? 'Pembelian di kantin/koperasi',
            ]);
        });

        return redirect()->back()->with('success', 'Transaksi berhasil.');
    }
}
