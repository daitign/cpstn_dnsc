<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\File;
use App\Models\Area;
use App\Models\Office;
use App\Models\AreaUser;
use App\Models\Evidence;
use App\Models\Directory;
use App\Models\AuditPlan;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DirectoryRepository;

class AuditController extends Controller
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
        $data = $this->dr->getDirectoryFiles($this->parent);
        $data['auditors'] = User::whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        
        return view('audits.index', $data);
    }

    public function createAuditPlan()
    {
        $auditors = User::whereDoesntHave('assigned_area')->whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        $tree_areas = $this->dr->getAreaFamilyTree(null, 'process');
        return view('audits.create', compact('tree_areas', 'auditors'));
    }

    public function saveAuditPlan(Request $request)
    {
        $area = Area::where('id', $request->area)->where('type', 'process')->firstOrFail();
        $audit_plan = AuditPlan::where('process_id', $area->id)->first();
        if(empty($audit_plan)) {
            $audit_plan = AuditPlan::create(['process_id' => $area->id]);
        }
        
        // Remove existing auditors from area
        AreaUser::where('area_id', $area->id)->whereHas('user', function($q) {
            $q->whereHas('role', function($sql){
                $sql->where('role_name', 'Internal Auditor');
            });
        })->delete();

        foreach($request->auditors as $auditor) {
            AreaUser::create([
                'user_id' => $auditor,
                'area_id' => $area->id,
            ]);
        }
        
        return back()->withMessage('Audit plan created successfully');
    }
}
