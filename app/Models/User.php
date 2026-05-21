<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function vendorUsers()
    {
        return $this->hasMany(VendorUser::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_users')
            ->withPivot('role', 'branch_id', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get the active branch of the user.
     */
    public function branch()
    {
        return $this->hasOneThrough(
            Branch::class,
            VendorUser::class,
            'user_id',
            'id',
            'id',
            'branch_id'
        );
    }
}
