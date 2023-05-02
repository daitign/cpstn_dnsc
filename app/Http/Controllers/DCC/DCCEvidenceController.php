<?php

namespace App\Http\Controllers\DCC;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\EvidencePermission;
use App\Models\Process;
use App\Models\Program;
use App\Rules\NoSameFolderName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class DCCEvidenceController extends Controller
{
    public function showProgramEvidence()
    {
        $programs = Auth::user()->program_user;
        $offices = Auth::user()->office_user;

        return view('Dcc.evidence.program',[
            'programs'=>$programs,
            'offices'=>$offices
        ]);
    }

    public function showOfficeProcess($office)
    {
        $processes = Process::query()
        ->whereRaw('office_id = (SELECT id FROM offices WHERE office_name = ?)',[$office])
        ->get();
        return view('Dcc.evidence.process',[
            'processes'=>$processes,
            'office'=>$office
        ]);
    }

    public function showProgramProcess($program)
    {
        $processes = Process::query()
        ->whereRaw('program_id = (SELECT id FROM programs WHERE program_name = ?)',[$program])
        ->get();
        return view('Dcc.evidence.process',[
            'processes'=>$processes,
            'program'=>$program
        ]);
    }

    public function evidenceProcess($program,$process)
    {
        $evidence = Evidence::query()
        ->where('process_id',$process)
        ->whereNull('evidence_id')
        ->get();

        $process_name = Process::query()
        ->where('id',$process)
        ->value('process_name');

        return view('dcc.evidence.directory',[
            'evidence'=>$evidence,
            'process'=>$process_name
        ]);
    }

    public function evidenceDirectories($program,$process,$parent)
    {
        $evidence = Evidence::query()
        ->with(['directory','evidence_remarks.user'])
        ->where('evidence_id',$parent)
        ->get();

        $process_name = Process::query()
        ->where('id',$process)
        ->value('process_name');

        $breadcrumbList = $this->getFolderName(array(),$parent);

        $breadcrumbList = array_reverse($breadcrumbList,true);

        return view('dcc.evidence.directory',[
            'evidence'=>$evidence,
            'process'=>$process_name,
            'folders'=>$breadcrumbList
        ]);
    }

    public function getFolderName(array $arr, int $id)
    {
        $evidence = Evidence::query()
        ->select('id','folder_name','evidence_id')
        ->where('id',$id)
        ->first();
        $arr[$evidence['id']] = $evidence['folder_name'];
        if (!$evidence['evidence_id']) {
            return $arr;
        }
        else{
            return $this->getFolderName($arr,$evidence['evidence_id']);
        }
    }

    public function addEvidenceFolder(Request $request)
    {
        $validatedData = $request->validate([
            'process'=>['required','exists:processes,id'],
            'parent'=>['nullable','exists:evidence,id'],
            'folder'=>['required','string','max:255',new NoSameFolderName($request->parent)],
        ]);
        
        Evidence::create([
            'process_id'=>$validatedData['process'],
            'evidence_id'=>$validatedData['parent'],
            'folder_name'=>$validatedData['folder'],
            'user_id'=>auth()->id()
        ]);
        
        return redirect()->back()->with('success','Folder successfully added');
    }

    public function renameEvidenceFolder(Request $request)
    {
        $validatedData = $request->validate([
            'id'=>['required','exists:evidence,id'],
            'folder'=>['required','string','max:255',new NoSameFolderName(request()->route('parent'))],
        ]);
        
        Evidence::query()
        ->where('id',$validatedData['id'])
        ->update([
            'folder_name'=>$validatedData['folder']
        ]);
        
        return redirect()->back()->with('success','Folder successfully renamed!');
    }

    public function removeEvidenceFolder(Request $request)
    {
        $validatedData = $request->validate([
            'id'=>['required','exists:evidence,id'],
        ]);
        Evidence::query()
        ->where('id',$validatedData['id'])
        ->delete();
        return redirect()->back()->with('success','Folder successfully removed!');
    }
}
