<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StudentWallet extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'student_id', 'balance', 'card_number', 'is_active'];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    /**
     * Deduct balance and record transaction.
     */
    public function deduct(float $amount, string $description, string $type = 'debit', string $referenceType = null, int $referenceId = null)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($amount, $description, $type, $referenceType, $referenceId) {
            $this->refresh(); // Get latest balance

            if ($this->balance < $amount) {
                throw new \Exception('Saldo tidak mencukupi.');
            }

            $balanceBefore = $this->balance;
            $this->decrement('balance', $amount);
            $balanceAfter = $this->balance;

            return $this->transactions()->create([
                'tenant_id' => $this->tenant_id,
                'wallet_id' => $this->id,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
        });
    }
}
