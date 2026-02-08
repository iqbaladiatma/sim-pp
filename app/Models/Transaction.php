<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'student_bill_id',
        'amount',
        'payment_method',
        'reference_number',
        'payment_date',
        'status',
        'recorded_by',
        'note'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function bill()
    {
        return $this->belongsTo(StudentBill::class, 'student_bill_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
