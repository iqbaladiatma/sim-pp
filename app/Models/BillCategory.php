<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BillCategory extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'default_amount', 'type', 'description'];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function studentBills()
    {
        return $this->hasMany(StudentBill::class);
    }
}
