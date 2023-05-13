<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Area;
use App\Models\Survey;
use App\Models\User;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboardPage()
    {
        $data = (object) [
            'office' => Area::offices()->count(),
            'surveys' => Survey::count(),
            'users' => User::count(),
            'files' => File::count(),
        ];
        return view('administrators.dashboard', compact('data'));
    }
}
