<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
   use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'regno',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     /**
     * Check if admin is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Check if admin has any admin management permission
     */
    public function canManageAdmins(): bool
    {
        return $this->hasAnyPermission(['view admins', 'create admins', 'edit admins', 'delete admins']);
    }

    /**
     * Check if admin can manage roles
     */
    public function canManageRoles(): bool
    {
        return $this->hasAnyPermission(['view roles', 'create roles', 'edit roles', 'delete roles']);
    }

    /**
     * Get admin's role names as string
     */
    public function getRoleNamesAttribute(): string
    {
        return $this->roles->pluck('name')->join(', ');
    }
}
