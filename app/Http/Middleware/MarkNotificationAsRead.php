<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;

// class MarkNotificationAsRead
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if($request->has('read')) {
//             $path = $request->path();
//             $notification = $request->user()->notifications()->where('id', $request->read)->first();
//             if($notification) {
//                 $notification->markAsRead();
//             }
//             return redirect()->to($path);
//         }
//         return $next($request);
//     }
// }

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MarkNotificationAsRead
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('read') && $request->user()) {
            $path = $request->path();
            $notification = $request->user()->notifications()->where('id', $request->read)->first();
            if ($notification) {
                $notification->markAsRead();
            }
            return redirect()->to($path);
        }

        return $next($request);
    }
}

