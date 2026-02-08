<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TeacherAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'teacher_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
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
