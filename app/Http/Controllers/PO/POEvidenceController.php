<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use App\Models\Directory;
use App\Models\Evidence;
use App\Models\EvidenceHistory;
use App\Models\Office;
use App\Models\Process;
use App\Models\ProcessUser;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class POEvidenceController extends Controller
{
    public function index()
    {
        $data = User::with(['process_user.office','process_user.program'])
        ->where('id',auth()->id())
        ->first();
        return view('PO.evidence',['assigned'=>$data]);
    }

    public function office_process()
    {
        $data = ProcessUser::query()
        ->select('processes.*')
        ->join('processes','processes.id','process_user.process_id')
        ->where('user_id',auth()->id())
        ->whereNotNull('office_id')
        ->get();
        return view('PO.evidences.office',[
            'office'=>$data,
            'office_selected'=>Office::where('id',request()->route('office'))->value('office_name')
        ]);
    }

    public function program_process()
    {
        $data = ProcessUser::query()
        ->select('processes.*')
        ->join('processes','processes.id','process_user.process_id')
        ->where('user_id',auth()->id())
        ->whereNotNull('program_id')
        ->get();
        return view('PO.evidences.program',[
            'program'=>$data,
            'program_selected'=>Program::where('id',request()->route('program'))->value('program_name')
        ]);
    }

    public function root_office_folder()
    {
        $data = Evidence::query()
        ->join('processes','processes.id','evidence.process_id')
        ->whereNull('evidence.directory_id')
        ->whereNull('evidence.evidence_id')
        ->whereNull('processes.program_id')
        ->select('evidence.*')
        ->get();
        return view('PO.evidences.offices.root',[
            'folder'=>$data,
            'process'=>Process::where('id',request()->route('process'))->first(),
            'office'=>Office::where('id',request()->route('office'))->value('office_name')
        ]);
    }

    public function root_program_folder()
    {
        $data = Evidence::query()
        ->join('processes','processes.id','evidence.process_id')
        ->whereNull('evidence.directory_id')
        ->whereNull('processes.office_id')
        ->whereNull('evidence.evidence_id')
        ->select('evidence.*')
        ->get();
        return view('PO.evidences.programs.root',[
            'folder'=>$data,
            'process'=>Process::where('id',request()->route('process'))->first(),
            'program'=>Program::where('id',request()->route('program'))->value('program_name')
        ]);
    }

    public function parent_program_folder(Request $request)
    {
        $folders = Evidence::query()
        ->whereNull('directory_id')
        ->where('evidence_id',$request->parent)
        ->get();
        $files = Evidence::query()
        ->with(['directory','evidence_histories','evidence_remarks.user','evidence_downloads.user','user','process'])
        ->where('user_id',auth()->id())
        ->where('evidence_id',$request->parent)
        ->whereNotNull('directory_id')
        ->get();
        
        return view('PO.evidences.programs.directory',[
            'folders'=>$folders,
            'files'=>$files,
            'navs'=>array_reverse($this->getFolderName(array(),$request->parent),true),
            'process'=>Process::where('id',request()->route('process'))->first(),
            'program'=>Program::where('id',request()->route('program'))->value('program_name')
        ]);
    }

    public function uploadEvidence(Request $request)
    {
        $validatedData = $request->validate([
            'parent'=>['required','exists:evidence,id'],
            'process'=>['required','exists:processes,id'],
            'filename'=>['required','string','max:255'],
            'file'=>['required','file','max:50000'],
        ]);
        $filename = Uuid::uuid4()->toString();
        $path = Storage::putFileAs('public/profiles',$request->file('file'),$filename.'.'.$request->file('file')->extension());
        $directory = Directory::create([
            'location'=>$path,
            'filename'=>$validatedData['filename'],
            'original_name'=>$filename,
            'extension'=>$request->file('file')->extension()
        ]);
        
        Evidence::create([
            'process_id'=>$validatedData['process'],
            'directory_id'=>$directory->id,
            'user_id'=>auth()->id(),
            'evidence_id'=>$validatedData['parent'],
        ]);

        return redirect()->back()->with('success','File uploaded successfully!');
        
    }

    public function updateName(Request $request)
    {
        $validatedData = $request->validate([
            'evidence' => ['required','exists:evidence,id'],
            'filename' => ['required','string','max:255']
        ]);

        Directory::query()
        ->where('id',$validatedData['evidence'])
        ->update([
            'filename'=>$validatedData['filename']
        ]);

        return redirect()->back()->with('success','File renamed successfully!');
    }

    public function updateFile(Request $request)
    {

        $validatedData = $request->validate([
            'evidence' => ['required','exists:evidence,id'],
            'file_id'=>['required','exists:directories,id'],
            'filename'=>['required','string','max:255'],
            'file'=>['required','file','max:50000'],
        ]);

        try {
            DB::beginTransaction();
            Evidence::query()
            ->where('id',$validatedData['evidence'])
            ->update([
                'updated_at'=>now()
            ]);
            

            EvidenceHistory::insertUsing(['evidence_id','location','filename','original_name','extension','created_at','updated_at'],function($query)use($validatedData){
                $evidence = $validatedData["evidence"];
                $query->select(DB::raw("$evidence"),'location','filename','original_name','extension','created_at',DB::raw('now()'));
                $query->from('directories');
                $query->where('id',$validatedData['file_id']);
            });

            $filename = Uuid::uuid4()->toString();
            $path = Storage::putFileAs('public/profiles',$request->file('file'),$filename.'.'.$request->file('file')->extension());

            Directory::query()
            ->where('id',$validatedData['file_id'])
            ->update([
                'location'=>$path,
                'filename'=>$validatedData['filename'],
                'original_name'=>$filename,
                'extension'=>$request->file('file')->extension()
            ]);

            DB::commit();
            return redirect()->back()->with('success','File uploaded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('err',$e->getMessage());
        }
        
    }

    public function deleteFile(Request $request)
    {
        $validatedData = $request->validate([
            'id'=>['required','exists:evidence,id']
        ]);

        Evidence::query()
        ->where('id',$validatedData['id'])
        ->delete();
        return redirect()->back()->with('success','File deleted successfully!');

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
}
