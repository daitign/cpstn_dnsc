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
        $data = $this->dr->getDirectoryFiles($this->parent);

        return view('archives.files', $data);
    }

    public function create()
    {
        $tree_areas = $this->dr->getAreaFamilyTree();
        return view('templates.create', compact('tree_areas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $parent_directory = Directory::where('name', $this->parent)->whereNull('parent_id')->firstOrFail();
        $area = Area::findOrFail($request->area);

        $dir = $this->dr->makeDirectory($area, $parent_directory->id);
        
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
                'description' => $request->description
            ]);

            $file_id = $file->id;
        }

        Template::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $user->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);

        
        return back()->withMessage('Template created successfully');
    }
}
