<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Survey;
use App\Models\SurveyOffice;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    //
    public function create()
    {
        $offices = Office::get();
        return view('surveys.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $survey = Survey::create([
            'name' => $request->fullname,
            'contact_number' => $request->contact_number,
            'type' => $request->type,
            'course_year' => $request->course_year ?? '',
            'occupation' => $request->occupation ?? '',
            'suggestions' => $request->suggestions ?? '',
        ]);

        SurveyOffice::create([
            'survey_id' => $survey->id,
            'office_id' => $request->office,
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
