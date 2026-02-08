<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'merchant_name',
        'description',
        'reference_id'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function wallet()
    {
        return $this->belongsTo(StudentWallet::class);
    }
}
