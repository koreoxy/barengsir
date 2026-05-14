<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorUser extends Model
{
    protected $fillable = [
        'user_id',
        'vendor_id',
        'branch_id',
        'role',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
