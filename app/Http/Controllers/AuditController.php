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
use App\Models\AuditReport;
use App\Models\AuditPlanUser;
use App\Models\AuditPlanArea;
use App\Models\AuditPlanDirectory;
use App\Models\ConsolidatedAuditReport;
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
        $auditors = User::whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        $audit_plans = AuditPlan::get();
        return view('audits.index', compact('audit_plans', 'auditors'));
    }

    public function createAuditPlan()
    {
        $auditors = User::whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        $tree_areas = $this->dr->getAreaFamilyTree(null, 'process');
        return view('audits.create', compact('tree_areas', 'auditors'));
    }

    public function editAuditPlan($id)
    {
        $auditors = User::whereHas('role', function($q) { $q->where('role_name', 'Internal Auditor'); })->get();
        $audit_plan = AuditPlan::findOrFail($id);
        return view('audits.edit', compact('auditors', 'audit_plan'));
    }

    public function saveAuditPlan(Request $request, $id = null)
    {
        $selected_areas = explode(',',$request->areas);
        $areas = Area::whereIn('id', $selected_areas)->where('type', 'process')->get();
        $audit_plan = AuditPlan::where('id', $id)->first();
        if(empty($id)) {
            $audit_plan = AuditPlan::create(['name' => $request->name]);
        }
        $audit_plan->name = $request->name;
        $audit_plan->description = $request->description;
        $audit_plan->date = $request->date;
        $audit_plan->save();
        
        AuditPlanUser::where('audit_plan_id', $audit_plan->id)->delete();
        AuditPlanDirectory::where('audit_plan_id', $audit_plan->id)->delete();

        foreach($request->auditors as $auditor) {
            AuditPlanUser::create([
                'user_id' => $auditor,
                'audit_plan_id' => $audit_plan->id,
            ]);
            
            $user = User::find($auditor);
            \Notification::notify($user, 'Assigned you to audit plan '.$request->name);

            foreach($areas as $area) {
                $directories = Directory::where('area_id', $area->id)->get();
                foreach($directories as $directory) {
                    if($this->dr->getGrandParent($directory) == 'Evidences') {
                        $dir = $this->dr->getDirectory($request->name, $directory->id, $area->id);
                        AuditPlanDirectory::create([
                            'directory_id' => $dir->id,
                            'audit_plan_id' => $audit_plan->id,
                        ]);
                    }
                }
            }
        }

        foreach($areas as $area) {
            AuditPlanArea::create([
                'area_id' => $area->id,
                'audit_plan_id' => $audit_plan->id,
            ]);
        }
        
        return back()->withMessage('Audit plan saved successfully');
    }

    public function auditReports(Request $request, $directory_name = '')
    {
        $user = Auth::user();

        if(!empty($request->directory)) {
            $data = $this->dr->getArchiveDirectoryaAndFiles($request->directory);
            $data['route'] = 'audit-reports';
            $data['page_title'] = 'Audit Reports';
            return view('archives.index', $data);
        }

        $data = $this->dr->getDirectoryFiles('Audit Reports');
        $data['page_title'] = 'Audit Reports';
        $data['route'] = 'audit-reports';

        return view('archives.files', $data);
    }

    public function createAuditReport()
    {
        return view('audit-reports.create');
    }

    public function storeAuditReport(Request $request)
    {
        $user = Auth::user();

        $parent_directory = Directory::where('name', 'Audit Reports')->whereNull('parent_id')->firstOrFail();

        $user = Auth::user();
        $dir = $this->dr->makeDirectory($user->assigned_area, $parent_directory->id);

        $year = Carbon::parse($request->date)->format('Y');
        $directory = $this->dr->getDirectory($year, $dir->id);
        $file_id = null;
        if ($request->hasFile('file_attachment')) {
            $now = Carbon::now();
            $file = $request->file('file_attachment');
            $hash_name = md5($file->getClientOriginalName() . uniqid());
            $target_path = sprintf('attachments/%s/%s/%s/%s', $now->year, $now->month, $now->day, $hash_name);
            $path = Storage::put($target_path, $file);
            $file_name = $request->name.".".$file->getClientOriginalExtension();

            $file = File::create([
                'directory_id' => $directory->id,
                'user_id' => $user->id,
                'file_name' => $file_name,
                'file_mime' => $file->getClientMimeType(),
                'container_path' => $path,
                'description' => $request->description,
                'type' => 'audit_reports'
            ]);
            $file_id = $file->id;
        }

        AuditReport::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);

        $users = User::whereHas('role', function($q){ $q->whereIn('role_name', \FileRoles::AUDIT_REPORTS); })->get();
        \Notification::notify($users, 'Submitted Audit Report');

        
        return back()->withMessage('Audit report created successfully');
    }

    public function storeConsolidatedAuditReport(Request $request)
    {
        $user = Auth::user();

        $parent_directory = Directory::where('name', 'Consolidated Audit Reports')->whereNull('parent_id')->firstOrFail();

        $user = Auth::user();
        $directory = $this->dr->makeDirectory($user->assigned_area, $parent_directory->id);

        $file_id = null;
        if ($request->hasFile('file_attachment')) {
            $now = Carbon::now();
            $file = $request->file('file_attachment');
            $hash_name = md5($file->getClientOriginalName() . uniqid());
            $target_path = sprintf('attachments/%s/%s/%s/%s', $now->year, $now->month, $now->day, $hash_name);
            $path = Storage::put($target_path, $file);
            $file_name = $request->name.".".$file->getClientOriginalExtension();

            $file = File::create([
                'directory_id' => $directory->id,
                'user_id' => $user->id,
                'file_name' => $file_name,
                'file_mime' => $file->getClientMimeType(),
                'container_path' => $path,
                'description' => $request->description,
                'type' => 'consolidated_audit_reports'
            ]);
            $file_id = $file->id;
        }

        ConsolidatedAuditReport::create([
            'name' => $request->name,
            'audit_report_id' => $request->audit_report_id,
            'description' => $request->description,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);

        $users = User::whereHas('role', function($q){ $q->whereIn('role_name', \FileRoles::CONSOLIDATED_AUDIT_REPORTS); })->get();
        \Notification::notify($users, 'Submitted Consolidated Audit Report');
        
        return back()->withMessage('Consolidated Audit report created successfully');
    }
}
