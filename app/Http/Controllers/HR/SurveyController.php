<?php

namespace App\Http\Controllers\HR;

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

    public function reports(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $date_from = $request->date_from ?? Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $date_to = $request->date_to ??  Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $surveys = $this->getSurvey($keyword, $date_from, $date_to);
        $by_areas = $surveys->groupBy('score.area');
        $areas = [];
        foreach($by_areas as $key => $area_surveys) {
            $area = (object) [
                'name' => collect($area_surveys)->first()->score->area->area_name,
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'total' => count($area_surveys),
            ];
            $areas[] = $area;
        }
        $areas = collect($areas);

        $by_type = $surveys->groupBy('type');
        $types = [];
        foreach($by_type as $key => $type_surveys) {
            $type = (object) [
                'name' => collect($type_surveys)->first()->type,
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'total' => count($type_surveys),
            ];
            $types[] = $type;
        }
        $types = collect($types);
        
        return view('HR.report', compact('surveys', 'keyword', 'date_from', 'date_to', 'areas', 'types'));
    }

    private function getSurvey($keyword, $date_from, $date_to){
       return Survey::with(['score.area'])
            ->whereHas('score.area',function($q) use($keyword){
                $q->where('area_name', 'LIKE', "%$keyword%");
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
