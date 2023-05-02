<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $roles = Role::get();
        $request_role = $request->role ?? '';
        $users = User::with('role');
        if(!empty($request_role)) {
            $users = $users->whereHas('role', function($q) use($request_role){
                $q->where('role_name', $request_role);
            });
        }
        $users = $users->get();

        return view('administrators.user', compact('users', 'roles' ,'request_role'));
    }
}
