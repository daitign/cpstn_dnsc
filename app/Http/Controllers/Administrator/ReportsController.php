<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\File;
use App\Models\User;
use App\Models\SurveyReport;
use App\Models\ConsolidatedAuditReport;

class ReportsController extends Controller
{
    public function surveyReports()
    {
        $survey_reports = SurveyReport::where('status', 'pending')->get();
        $files = File::where('type', 'survey_reports')->get();
        return view('survey-reports.index', compact('survey_reports', 'files'));
    }

    public function approveSurveyReport($id)
    {
        $report = SurveyReport::findOrFail($id);
        $report->status = 'pre-approved';
        $report->updated_by = Auth::user()->id;
        $report->save();
        
        $user = User::find($report->user_id);
        \Notification::notify($user, 'Pre-approved survey report');
        
        return back()->withMessage('Survey report pre-approved successfully');
    }

    public function rejectSurveyReport($id)
    {
        $report = SurveyReport::findOrFail($id);
        $report->status = 'rejected';
        $report->updated_by = Auth::user()->id;
        $report->save();
        
        $user = User::find($report->user_id);
        \Notification::notify($user, 'Rejected survey report');
        
        return back()->withMessage('Survey report rejected successfully');
    }

    public function consolidatedAuditReports()
    {
        $consolidated_audit_reports = ConsolidatedAuditReport::where('status', 'pending')->get();
        $files = File::where('type', 'survey_reports')->get();
        return view('consolidated-audit-reports.index', compact('consolidated_audit_reports', 'files'));
    }

    public function approveConsolidatedAuditReport($id)
    {
        $report = ConsolidatedAuditReport::findOrFail($id);
        $report->status = 'pre-approved';
        $report->updated_by = Auth::user()->id;
        $report->save();

        $user = User::find($report->user_id);
        \Notification::notify($user, 'Pre-approved consolidated audit report');
        
        return back()->withMessage('Consolidated audit report approved successfully');
    }

    public function rejectConsolidatedAuditReport($id)
    {
        $report = ConsolidatedAuditReport::findOrFail($id);
        $report->status = 'rejected';
        $report->updated_by = Auth::user()->id;
        $report->save();

        
        $user = User::find($report->user_id);
        \Notification::notify($user, 'Rejected consolidated audit report');
        
        return back()->withMessage('Consolidated audit report rejected successfully');
    }
}
