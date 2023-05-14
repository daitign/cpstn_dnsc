<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Survey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $surveys = Survey::with(['score.area'])
                        ->whereHas('score.area',function($q) use($keyword){
                            $q->where('area_name', 'LIKE', "%$keyword%");
                        })->get();
        return view('HR.surveys',[
            'surveys' => $surveys,
            'keyword' => $keyword
        ]);
    }
}
