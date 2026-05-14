<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class Product extends Model
{
    use HasBranch;
    protected $fillable = [
        'category_id', 'brand_id', 'branch_id', 'name', 'sku', 'description', 
        'purchase_price', 'selling_price', 'stock', 'image'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
