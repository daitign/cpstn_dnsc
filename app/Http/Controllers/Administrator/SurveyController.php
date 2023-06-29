<?php

namespace App\Http\Controllers\Administrator;

use Carbon\Carbon;
use App\Models\Survey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $date_from = $request->date_from ?? Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $date_to = $request->date_to ??  Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $surveys = $this->getSurvey($keyword, $date_from, $date_to);
        
        return view('HR.surveys', compact('surveys', 'keyword', 'date_from', 'date_to'));
    }

    private function getSurvey($keyword, $date_from, $date_to){
        return Survey::with(['facility'])
             ->whereHas('facility',function($q) use($keyword){
                 $q->where('name', 'LIKE', "%$keyword%");
             })->where(function($q) use($date_from, $date_to){
                 if(!empty($date_from)) {
                     $q->where('created_at', '>=', $date_from);
                 }
                 if(!empty($date_to)) {
                     $q->where('created_at', '<=', $date_to);
                 }
         })->get();
     }
}
