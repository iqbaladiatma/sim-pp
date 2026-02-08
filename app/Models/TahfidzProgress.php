<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TahfidzProgress extends Model
{
    use HasFactory;

    protected $table = 'tahfidz_progress';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'surah_name',
        'start_verse',
        'end_verse',
        'type',
        'quality',
        'recorded_by',
        'recorded_at',
        'note'
    ];

    protected $casts = [
        'recorded_at' => 'date',
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

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
