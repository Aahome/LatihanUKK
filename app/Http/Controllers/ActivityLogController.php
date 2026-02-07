<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class ActivityLogController extends Controller
{
    public function index()
    {
        // ActivityLog::create([
        //     'user_id' => Auth::id(),
        //     'activity' => 'Admin viewed activity logs',
        // ]); 
        // activity_log('Test log works');


        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.logs.index', compact('logs'));
    }
}
