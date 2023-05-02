<?php

namespace App\Http\Controllers\Staff;

use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function dashboard()
    {
        $data = (object) [
            'files' => File::where('user_id', Auth::user()->id)->count(),
        ];

        return view('staff.dashboard', compact('data'));
    }
}
