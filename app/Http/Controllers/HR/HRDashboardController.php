<?php

namespace App\Http\Controllers\HR;

use App\Models\Office;
use App\Models\Survey;
use App\Models\User;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HRDashboardController extends Controller
{
    public function dashboard()
    {
        $data = (object) [
            'office' => Office::count(),
            'surveys' => Survey::count(),
            'users' => User::count(),
            'files' => File::count(),
        ];

        return view('HR.dashboard', compact('data'));
    }
}
