<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchSelectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        $branches = $user->vendorUsers()
            ->whereHas('vendor', function($query) {
                $query->where('is_active', true);
            })
            ->with(['branch', 'vendor.branches'])
            ->get()
            ->flatMap(function($vu) {
                if ($vu->role === 'owner') {
                    return $vu->vendor->branches;
                }
                return $vu->branch ? [$vu->branch] : [];
            })
            ->unique('id');

        if ($branches->count() === 0) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Anda tidak memiliki akses ke cabang manapun.']);
        }

        if ($branches->count() === 1) {
            $this->setBranchSession($branches->first());
            return redirect()->route('dashboard');
        }

        return view('auth.select-branch', compact('branches'));
    }

    public function setBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);

        $branch = Branch::findOrFail($request->branch_id);
        
        // Verify user has access to this branch
        $hasAccess = auth()->user()->vendorUsers()
            ->where(function($query) use ($branch) {
                $query->where('branch_id', $branch->id)
                      ->orWhere(function($q) use ($branch) {
                          $q->where('vendor_id', $branch->vendor_id)
                            ->where('role', 'owner');
                      });
            })
            ->exists();

        if (!$hasAccess) {
            abort(403);
        }

        $this->setBranchSession($branch);

        return redirect()->route('dashboard');
    }

    private function setBranchSession($branch)
    {
        session(['active_branch_id' => $branch->id]);
        session(['active_branch_name' => $branch->name]);
        session(['active_vendor_id' => $branch->vendor_id]);
        
        $vu = auth()->user()->vendorUsers()
            ->where('vendor_id', $branch->vendor_id)
            ->where(function($q) use ($branch) {
                $q->where('branch_id', $branch->id)->orWhereNull('branch_id');
            })
            ->orderBy('role', 'asc') // Owner > Admin > Cashier (based on alphabetical order of enum if lucky, but better define order)
            ->first();
            
        session(['active_role' => $vu->role]);
    }
}
