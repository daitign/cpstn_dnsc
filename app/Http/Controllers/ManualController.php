<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\File;
use App\Models\Office;
use App\Models\Manual;
use App\Models\Directory;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DirectoryRepository;

class ManualController extends Controller
{
    private $parent = 'Manuals';
    private $dr;

    public function __construct() 
    {
        $this->dr = new DirectoryRepository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if(!empty($request->directory)) {
            $data = $this->dr->getArchiveDirectoryaAndFiles($request->directory);
            $data['route'] = 'manuals';
            $data['page_title'] = $this->parent;
            return view('archives.index', $data);
        }

        $data = $this->dr->getDirectoryFiles($this->parent);
        $data['route'] = 'manuals';
        $data['page_title'] = $this->parent;
        return view('archives.files', $data);
    }

    public function create()
    {
        $directories = [];
        if(Auth::user()->role->role_name == 'Process Owner') {
            $directories = $this->dr->getDirectoryAssignedByGrandParent($this->parent);
        }
        return view('manuals.create', compact('directories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if(Auth::user()->role->role_name == 'Process Owner') {
            $directories = $this->dr->getDirectoryAssignedByGrandParent($this->parent);
            $directory = $directories->where('id', $request->directory)->firstOrFail();
        }else{
            $parent_directory = Directory::where('name', $this->parent)->whereNull('parent_id')->firstOrFail();

            $user = Auth::user();
            $dir = $this->dr->makeDirectory($user->assigned_area, $parent_directory->id);
            $year = Carbon::parse($request->date)->format('Y');
            $directory = $this->dr->getDirectory($year, $dir->id);    
        }
        
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
                'type' => 'manuals'
            ]);
            $file_id = $file->id;
        }

        Manual::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);

        
        return back()->withMessage('Manual created successfully');
    }
}
