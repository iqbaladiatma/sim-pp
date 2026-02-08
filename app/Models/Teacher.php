<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'nip',
        'position',
        'specialization',
        'join_date',
        'salary',
        'status'
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
