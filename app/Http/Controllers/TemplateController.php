<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use App\Models\File;
use App\Models\Template;
use App\Models\Directory;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DirectoryRepository;

class TemplateController extends Controller
{
    private $parent = 'Templates';
    private $dr;

    public function __construct() 
    {
        $this->dr = new DirectoryRepository;
    }

    public function index(Request $request, $directory_name = '')
    {
        $user = Auth::user();
        if(!empty($request->directory)) {
            $data = $this->dr->getDirectoriesAndFiles('Templates', $request->directory);
            $data['route'] = 'templates';
            $data['page_title'] = $this->parent;
            return view('archives.index', $data);
        }
        
        $directory_id = null;
        if($user->role->role_name !== 'Staff') {
            $directory_id = Directory::whereHas('parent', function($q) {
                $q->where('name', $this->parent);
            })->where('name', $user->role->role_name)->firstOrFail()->id ?? null;
        }
        
        $data = $this->dr->getDirectoriesAndFiles('Templates', $directory_id, $request->user);
        $data['page_title'] = $this->parent;
        $data['route'] = 'templates';

        return view('archives.index', $data);
    }

    public function create()
    {
        $roles = Role::get();

        $tree_process = $this->dr->getAreaFamilyTree(null, 'process');
        $areas = Area::whereIn('type', ['institute', 'office'])->get()->groupBy('parent.area_name');

        return view('templates.create', compact('tree_process', 'areas', 'roles'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $role = Role::findOrFail($request->role);
        $areas = $role->role_name == 'Process Owner' ? explode(',', $request->process) : $request->institutes;
        
        $parent_directory = Directory::where('name', $this->parent)->whereNull('parent_id')->firstOrFail();
        $role = Role::findOrFail($request->role);
        $directory = $this->dr->getDirectory($role->role_name, $parent_directory->id);
        
        if ($request->hasFile('file_attachment')) {
            $now = Carbon::now();
            $file = $request->file('file_attachment');
            $hash_name = md5($file->getClientOriginalName() . uniqid());
            $target_path = sprintf('attachments/%s/%s/%s/%s', $now->year, $now->month, $now->day, $hash_name);
            $path = Storage::put($target_path, $file);
            $file_name = $request->name.".".$file->getClientOriginalExtension();

            if(in_array($role->role_name, ['Process Owner', 'Document Control Custodian'])) {
                $selected_areas = $role->role_name == 'Process Owner' ? explode(',', $request->process) : $request->institutes;
                $areas = Area::whereIn('id', $selected_areas)->get();
                foreach($areas as $area) {
                    $dir = $this->dr->makeAreaRootDirectories($area, $directory->id);
                    
                    File::create([
                        'directory_id' => $dir->id,
                        'user_id' => $user->id,
                        'file_name' => $file_name,
                        'file_mime' => $file->getClientMimeType(),
                        'container_path' => $path,
                        'description' => $request->description ?? '',
                        'type' => 'templates'
                    ]);
                }
            }else{
                $file = File::create([
                    'directory_id' => $directory->id,
                    'user_id' => $user->id,
                    'file_name' => $file_name,
                    'file_mime' => $file->getClientMimeType(),
                    'container_path' => $path,
                    'description' => $request->description ?? '',
                    'type' => 'templates'
                ]);
            }
        }

        Template::create([
            'name' => $request->name,
            'description' => $request->description ?? '',
            'user_id' => $user->id,
            'date' => $request->date,
            'role_id' => $request->role,
            'areas' => !empty($areas) ? implode(',', $areas->pluck('id')->toArray()) : ''
        ]);

        
        return back()->withMessage('Template created successfully');
    }
}
