<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'academic_year', 'semester', 'is_active'];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
