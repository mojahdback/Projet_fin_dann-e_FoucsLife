<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
    $userId  = session('auth_user_id');
    $filter  = $request->query('filter', 'all');

    $query = \App\Models\Notification::forUser($userId)
        ->orderBy('created_at', 'desc');

    if ($filter === 'unread') {
        $query->unread();
    } elseif ($filter === 'read') {
        $query->where('is_read', true);
    }

    $notifications = $query->get();
    $unreadCount   = \App\Models\Notification::forUser($userId)->unread()->count();

    return view('notifications.index', compact('notifications', 'unreadCount'));
    }
}
