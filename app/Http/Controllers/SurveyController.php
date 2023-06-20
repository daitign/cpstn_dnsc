<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use App\Models\Survey;
use App\Models\SurveyArea;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    //
    public function create(Request $request)
    {
        $prevSurvey = $request->session()->pull('survey');
        $facilities = Facility::get();
        return view('surveys.create', compact('facilities', 'prevSurvey'));
    }

    public function store(Request $request)
    {
        $survey = Survey::create([
            'email' => $request->email,
            'name' => $request->fullname,
            'contact_number' => $request->contact_number,
            'type' => $request->type,
            'course' => $request->course,
            'course_year' => $request->course_year ?? '',
            'occupation' => $request->occupation ?? '',
            'suggestions' => $request->suggestions ?? '',
            'facility_id' => $request->office,
            'promptness' => $request->promptness,
            'engagement' => $request->engagement,
            'cordiality' => $request->cordiality,
        ]);

        $request->session()->put('survey', $survey);

        $users = User::whereHas('role', function($q){ $q->whereIn('role_name', ['Human Resources']); })->get();
        \Notification::notify($users, 'Submitted Survey', $request->fullname);
        
        return redirect()->route('surveys.success');
    }

    public function success()
    {
        return view('surveys.success');
    }
}
