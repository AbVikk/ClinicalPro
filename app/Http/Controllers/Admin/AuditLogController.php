<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        // 1. Fetch Logs (Eager load 'user' to prevent N+1 query problem)
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50); // 50 items per page

        return view('admin.audit_logs.index', compact('logs'));
    }
}