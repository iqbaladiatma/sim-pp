<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'bill_category_id',
        'amount',
        'paid_amount',
        'due_date',
        'period_month',
        'status',
        'note'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

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

    public function category()
    {
        return $this->belongsTo(BillCategory::class, 'bill_category_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }
}
