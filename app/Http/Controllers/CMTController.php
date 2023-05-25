<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SurveyReport;
use Illuminate\Support\Facades\Auth;
use App\Models\ConsolidatedAuditReport;
class CMTController extends Controller
{
    //
    public function surveyReports()
    {
        $survey_reports = SurveyReport::where('status', 'pending')->get();
        return view('survey-reports.index', compact('survey_reports'));
    }

    public function approveSurveyReport($id)
    {
        $report = SurveyReport::findOrFail($id);
        $report->status = 'approved';
        $report->updated_by = Auth::user()->id;
        $report->save();
        
        return back()->withMessage('Survey report approved successfully');
    }

    public function rejectSurveyReport($id)
    {
        $report = SurveyReport::findOrFail($id);
        $report->status = 'rejected';
        $report->updated_by = Auth::user()->id;
        $report->save();
        
        return back()->withMessage('Survey report rejected successfully');
    }

    public function consolidatedAuditReports()
    {
        $consolidated_audit_reports = ConsolidatedAuditReport::where('status', 'pending')->get();
        return view('consolidated-audit-reports.index', compact('consolidated_audit_reports'));
    }

    public function approveConsolidatedAuditReport($id)
    {
        $report = ConsolidatedAuditReport::findOrFail($id);
        $report->status = 'approved';
        $report->updated_by = Auth::user()->id;
        $report->save();
        
        return back()->withMessage('Consolidated audit report approved successfully');
    }

    public function rejectConsolidatedAuditReport($id)
    {
        $report = ConsolidatedAuditReport::findOrFail($id);
        $report->status = 'rejected';
        $report->updated_by = Auth::user()->id;
        $report->save();
        
        return back()->withMessage('Consolidated audit report rejected successfully');
    }
}
