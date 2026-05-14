<?php

namespace App\Traits;

use App\Models\Scopes\BranchScope;

trait HasBranch
{
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());

        static::creating(function ($model) {
            if (session()->has('active_branch_id') && !$model->branch_id) {
                $model->branch_id = session('active_branch_id');
            }
        });
    }
}
