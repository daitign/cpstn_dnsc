<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAssignedArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if (
            Auth::guard($guard)->check() && 
            in_array(Auth::user()->role->role_name, config('app.role_with_assigned_area')) && 
            empty(Auth::user()->assigned_areas->area_name)){
                return redirect(route('unassigned'));
        }
        return $next($request);
    }
}
