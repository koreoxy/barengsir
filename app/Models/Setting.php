<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class Setting extends Model
{
    use HasBranch;
    protected $fillable = ['key', 'value', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
