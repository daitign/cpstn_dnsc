<?php

namespace App\Http\Controllers\DCC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DCCDashboardController extends Controller
{
    public function dashboard()
    {
        return view('Dcc.dashboard');
    }
}
