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
        $auditors = User::whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        $tree_areas = $this->dr->getAreaFamilyTree(null, 'process');
        return view('audits.create', compact('tree_areas', 'auditors'));
    }

    public function saveAuditPlan(Request $request)
    {
        $area = Area::where('id', $request->area)->where('type', 'process')->firstOrFail();
        $audit_plan = AuditPlan::where('area_id', $area->id)->first();
        if(empty($audit_plan)) {
            $audit_plan = AuditPlan::create(['area_id' => $area->id]);
        }
        

        foreach($request->auditors as $auditor) {
            // Remove existing auditors from area
            AreaUser::where('user_id', $auditor)->delete();

            AreaUser::create([
                'user_id' => $auditor,
                'area_id' => $area->id,
            ]);
        }
        
        return back()->withMessage('Audit plan created successfully');
    }
}
