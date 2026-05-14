<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class Category extends Model
{
    use HasBranch;
    protected $fillable = ['name', 'slug', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
