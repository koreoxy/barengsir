<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;
use App\Traits\Auditable;

class Transaction extends Model
{
    use HasBranch, Auditable;
    protected $fillable = [
        'invoice_number',
        'total_amount',
        'paid_amount',
        'payment_method',
        'change_amount',
        'user_id',
        'branch_id'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
