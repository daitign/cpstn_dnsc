<?php

namespace App\Http\Controllers;

use App\Models\AuditPlan;
use Illuminate\Http\Request;

class AuditorController extends Controller
{
    private $parent = 'Evidences';
    private $dr;

    public function __construct() 
    {
        $this->dr = new DirectoryRepository;
    }

    public function auditPlans(Request $request, $directory_name = '')
    {
        $user = Auth::user();
        $audit_plans = AuditPlan::get();
        return view('audits.index', compact('audit_plans'));
    }

    public function areas(Request $request, $id)
    {
        $user = Auth::user();
        $audi_plan = AuditPlan::findOrFail($id);
        return view('audits.index', compact('audi_plan'));
    }
}
