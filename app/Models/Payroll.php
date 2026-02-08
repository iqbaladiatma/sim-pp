<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'teacher_id',
        'month',
        'year',
        'base_salary',
        'allowances',
        'deductions',
        'total_salary',
        'payment_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'base_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_salary' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
