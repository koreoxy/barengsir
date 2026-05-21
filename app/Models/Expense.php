<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class Expense extends Model
{
    use HasBranch;

    protected $fillable = [
        'branch_id',
        'title',
        'category',
        'amount',
        'expense_date',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * Get the branch that owns the expense.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
