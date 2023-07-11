<?php
namespace App\Helpers;

use App\Constants\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UpdatesNotifications;
use Illuminate\Support\Facades\Notification;

class NotificationHelper {
    
    public static function notify($users, $message, $link = '', $username = '') {
        $user_id = !empty($username) ? '' : Auth::user()->id;

        if(empty($username)) {
            $username = !empty(Auth::user()) && Auth::user()->role->role_name !== Roles::QUALITY_ASSURANCE_DIRECTOR ? Auth::user()->full_name : Roles::QUALITY_ASSURANCE_DIRECTOR;
        }
        $message = $username.' '.$message;
        $link = !empty($link) ? $link : url('/dashboard');

        Notification::send($users, new UpdatesNotifications($message, $user_id, $username, $link));

        // Send also to administrator
        // $admin_users = User::whereHas('role', function($q){ $q->where('role_name', 'Administrator'); })->get();
        // Notification::send($admin_users, new UpdatesNotifications($message, '', $username));
    }
}