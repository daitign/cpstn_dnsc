<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Office;
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
            'office' => Office::count(),
            'surveys' => Survey::count(),
            'users' => User::count(),
            'files' => File::count(),
        ];
        return view('administrators.dashboard', compact('data'));
    }
}
