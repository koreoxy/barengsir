<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'code',
        'address',
        'phone',
        'is_active',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
