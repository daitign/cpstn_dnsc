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

    public function auditReports(Request $request, $directory_name = '')
    {
        $user = Auth::user();
        $data = $this->dr->getDirectoryFiles('Audit Reports');

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
                'type' => 'audit_reports'
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

        
        return back()->withMessage('Consolidated Audit report created successfully');
    }
}
