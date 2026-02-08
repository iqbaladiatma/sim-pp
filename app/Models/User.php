<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'avatar',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Global scope to filter by tenant_id (except for super_admin)
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    /**
     * Get the tenant that owns the user.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is pondok admin.
     */
    public function isAdminPondok(): bool
    {
        return $this->role === 'admin_pondok';
    }

    /**
     * Check if user is ustadz.
     */
    public function isUstadz(): bool
    {
        return $this->role === 'ustadz';
    }

    /**
     * Check if user is wali santri.
     */
    public function isWaliSantri(): bool
    {
        return $this->role === 'wali_santri';
    }

    /**
     * Check if user has admin privileges.
     */
    public function hasAdminPrivileges(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_pondok']);
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get user's full name with role.
     */
    public function getFullNameWithRoleAttribute(): string
    {
        $roleNames = [
            'super_admin' => 'Super Admin',
            'admin_pondok' => 'Admin Pondok',
            'ustadz' => 'Ustadz',
            'wali_santri' => 'Wali Santri',
            'staff' => 'Staff',
        ];

        return $this->name . ' (' . ($roleNames[$this->role] ?? $this->role) . ')';
    }
}
