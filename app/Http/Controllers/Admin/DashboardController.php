<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNotifications = Notification::count();
        $activeNotifications = Notification::where('is_active', true)->count();
        $totalDevices = DeviceToken::count();
        $todaysNotifications = Notification::whereDate('created_at', today())->count();
        $sentNotifications = NotificationLog::where('status', 'sent')->count();

        $recentNotifications = Notification::latest()->take(5)->get();
        $recentLogs = NotificationLog::with(['notification', 'deviceToken'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalNotifications',
            'activeNotifications',
            'totalDevices',
            'todaysNotifications',
            'sentNotifications',
            'recentNotifications',
            'recentLogs'
        ));
    }
}
