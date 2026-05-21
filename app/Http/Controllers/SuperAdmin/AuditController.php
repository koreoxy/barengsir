<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Show activity logs and audit trails lists with dynamic search filters.
     */
    public function index(Request $request)
    {
        $users = User::with('branch')->orderBy('name')->get();
        
        $tab = $request->input('tab', 'activity');

        // 1. Get filtered Activity Logs
        $activitiesQuery = ActivityLog::with('user.branch')->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $activitiesQuery->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $activitiesQuery->where(function($q) use ($request) {
                $q->where('activity', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('date')) {
            $activitiesQuery->whereDate('created_at', $request->date);
        }

        $activities = $activitiesQuery->paginate(20, ['*'], 'activity_page')->withQueryString();

        // 2. Get filtered Audit Trails
        $auditsQuery = AuditTrail::with('user.branch')->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $auditsQuery->where('user_id', $request->user_id);
        }
        if ($request->filled('event')) {
            $auditsQuery->where('event', $request->event);
        }
        if ($request->filled('date')) {
            $auditsQuery->whereDate('created_at', $request->date);
        }

        $audits = $auditsQuery->paginate(20, ['*'], 'audit_page')->withQueryString();

        return view('superadmin.audit.index', compact('activities', 'audits', 'users', 'tab'));
    }
}
