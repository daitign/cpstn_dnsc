<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\File;
use App\Models\Office;
use App\Models\Evidence;
use App\Models\Directory;
use Illuminate\Support\Facades\Auth;

class EvidenceController extends Controller
{
    public function index(Request $request, $directory_name = '')
    {

        $current_user = Auth::user();
        if(empty($current_user->assigned_area->area_name)) {
            return redirect(route('unassigned'));
        };

        $users = $current_user->role->role_name == 'Administrator' ? User::get() : User::where('role_id', $current_user->role_id)->get();
        $parent_directory = Directory::where('name', 'Evidences')->whereNull('parent_id')->firstOrFail();

        $directory = Directory::where('parent_id', $parent_directory->id)
                        ->where('name', $current_user->assigned_area->area_name)
                        ->first();

        if(!$directory) {
            $directory = Directory::create([
                'parent_id' => $parent_directory->id,
                'name' =>  $current_user->assigned_area->area_name
            ]);
        }

        $directories = Directory::where('parent_id', $directory->id)->get();
        
        $files = File::where('directory_id', $directory->id)
                    ->where('user_id', $current_user->id)
                    ->get();
        
        return view('archives.files', compact('files', 'current_user', 'users', 'directories', 'directory', 'parent_directory'));
    }

    public function create()
    {
        return view('evidences.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $parent_directory = Directory::where('name', 'Evidences')->whereNull('parent_id')->firstOrFail();

        $directory = Directory::where('parent_id', $parent_directory->id)
                        ->where('name', $user->assigned_area->area_name)->first();
        if(!$directory) {
            $directory = Directory::create([
                'parent_id' => $parent_directory->id,
                'name' =>  $user->assigned_area->area_name
            ]);
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
                'description' => $request->description
            ]);
            $file_id = $file->id;
        }
        

        Evidence::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);


        
        return back()->withMessage('Evidence created successfully');
    }
}
