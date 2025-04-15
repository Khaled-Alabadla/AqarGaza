<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function markAsRead(DatabaseNotification $notification)
    {
        // Mark the notification as read
        $notification->markAsRead();

        // Redirect to the property page
        return redirect()->route('dashboard.properties.user', Auth::id());
    }
}
