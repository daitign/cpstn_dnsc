<?php

namespace App\Http\Controllers\DCC;

use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DCCDashboardController extends Controller
{
    public function dashboard()
    {
        $data = (object) [
            'files' => File::where('user_id', Auth::user()->id)->count(),
        ];

        return view('Dcc.dashboard', compact('data'));
    }
}
