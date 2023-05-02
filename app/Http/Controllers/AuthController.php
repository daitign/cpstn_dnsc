<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required','exists:users,username'],
            'password' => ['required']
        ]);

        if (User::where('username',$request->username)->whereNotNull('role_id')->count() == 0) {
            return back()->withErrors([
                'username' => 'Your account is not yet approved',
            ])->onlyInput('username');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role->role_name;
            if ($role == 'Administrator') {
                return redirect()->route('admin-dashboard-page');
            }
            if ($role == 'Document Control Custodian') {# code...
                return redirect()->route('dcc-dashboard-page');
            }
            if ($role == 'Process Owner') {# code...
                return redirect()->route('dcc-dashboard-page');
            }
            if ($role == 'Staff') {# code...
                return redirect()->route('staff.dashboard');
            }
            if ($role == 'Human Resources') {# code...
                return redirect()->route('hr-dashboard-page');
            }
        }
 
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');

    }

    public function lg(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect()->route('login-page');
    }
}
