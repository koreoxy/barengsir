<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class StockMovement extends Model
{
    use HasBranch;
    protected $fillable = ['product_id', 'type', 'quantity', 'note', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
