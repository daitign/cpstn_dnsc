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
        if(in_array($user->role->role_name, ['Quality Assurance Director', 'Staff']) || !empty($request->directory)) {
            $data = $this->dr->getDirectoriesAndFiles($this->parent, $request->directory ?? null);
        }else{
            $template_dir = $this->dr->getDirectory('Templates');
            $directory = $this->dr->getDirectory($user->role->role_name, $template_dir->id);
            $data = $this->dr->getDirectoriesAndFiles($this->parent, $directory->id ?? null);
        }
        
        $data['route'] = strtolower($this->parent);
        $data['page_title'] = $this->parent;
        
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
        
        $directory = Directory::findOrFail($request->current_directory);        
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
                'description' => $request->description ?? '',
                'type' => 'templates'
            ]);
        }

        Template::create([
            'name' => $request->name,
            'description' => $request->description ?? '',
            'user_id' => $user->id,
            'date' => $request->date
        ]);

        
        return back()->withMessage('Template created successfully');
    }
}
