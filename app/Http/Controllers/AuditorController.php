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

    public function index(Request $request, $directory_name = '')
    {
        $user = Auth::user();
        $auditors = User::whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        $audit_plans = AuditPlan::get();
        return view('audits.index', compact('audit_plans', 'auditors'));
    }
}
