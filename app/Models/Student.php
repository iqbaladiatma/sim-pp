<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'nis',
        'nisn',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone_number',
        'parent_id',
        'classroom_id',
        'dormitory_room_id',
        'entry_date',
        'status',
        'photo',
        'discipline_points',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function dormitoryRoom()
    {
        return $this->belongsTo(DormitoryRoom::class);
    }
}
