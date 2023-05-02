<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PODashboardController extends Controller
{
    public function dashboard()
    {
        return view('PO.dashboard');
    }
}
