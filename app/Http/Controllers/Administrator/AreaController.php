<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function adminAreaPage()
    {
        return view('administrators.area',[
            'data' => Area::with(['children'])
            ->get()
        ]);
    }
}
