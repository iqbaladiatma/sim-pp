<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'address',
        'phone',
        'email',
        'logo',
        'subscription_plan',
        'subscription_status',
        'trial_ends_at',
        'subscribed_at',
        'max_students',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscribed_at' => 'datetime',
        'settings' => 'array',
    ];

    /**
     * Get the users for the tenant.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the students for the tenant.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Check if tenant is active.
     */
    public function isActive(): bool
    {
        return $this->subscription_status === 'active';
    }

    /**
     * Check if tenant is on trial.
     */
    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial'
            && $this->trial_ends_at
            && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if trial has expired.
     */
    public function trialExpired(): bool
    {
        return $this->subscription_status === 'trial'
            && $this->trial_ends_at
            && $this->trial_ends_at->isPast();
    }

    /**
     * Get the full subdomain URL.
     */
    public function getSubdomainUrlAttribute(): string
    {
        return $this->slug . '.' . config('app.domain');
    }

    /**
     * Get the full URL (custom domain or subdomain).
     */
    public function getFullUrlAttribute(): string
    {
        if ($this->domain) {
            return 'https://' . $this->domain;
        }

        return 'https://' . $this->subdomain_url;
    }

    /**
     * Check if tenant has reached student limit.
     */
    public function hasReachedStudentLimit(): bool
    {
        return $this->students()->count() >= $this->max_students;
    }

    /**
     * Get remaining student slots.
     */
    public function getRemainingStudentSlotsAttribute(): int
    {
        return max(0, $this->max_students - $this->students()->count());
    }
}
