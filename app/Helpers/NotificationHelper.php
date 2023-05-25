<?php
namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UpdatesNotifications;
use Illuminate\Support\Facades\Notification;

class NotificationHelper {
    
    public static function notify($users, $message, $username = '') {
        $user_id = !empty($username) ? '' : Auth::user()->id;
        $username = !empty($username) ? $username : Auth::user()->username;
        Notification::send($users, new UpdatesNotifications($message, $user_id, $username));

        // Send also to administrator
        $admin_users = User::whereHas('role', function($q){ $q->where('role_name', 'Administrator'); })->get();
        Notification::send($admin_users, new UpdatesNotifications($message, '', $username));
    }
}