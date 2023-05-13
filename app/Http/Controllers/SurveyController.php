<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Survey;
use App\Models\SurveyOffice;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    //
    public function create()
    {
        $offices = Area::offices()->get();
        return view('surveys.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $survey = Survey::create([
            'name' => $request->fullname,
            'contact_number' => $request->contact_number,
            'type' => $request->type,
            'course' => $request->course,
            'course_year' => $request->course_year ?? '',
            'occupation' => $request->occupation ?? '',
            'suggestions' => $request->suggestions ?? '',
        ]);

        SurveyOffice::create([
            'survey_id' => $survey->id,
            'area_id' => $request->office,
            'promptness' => $request->promptness,
            'engagement' => $request->engagement,
            'cordiality' => $request->cordiality,
        ]);

        return redirect()->route('surveys.success');
    }

    public function success()
    {
        return view('surveys.success');
    }
}
