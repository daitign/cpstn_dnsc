<?php

namespace App\Http\Controllers;

use Storage;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\FileUser;
use App\Models\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    public function index(Request $request, $directory_name = '')
    {
        $users = [];
        $files = [];
        $parents = [];
        $current_directory = $request->directory;
        $users = User::get();
        $current_user = !empty($request->user) ? User::findOrFail($request->user) : Auth::user();

        $directory_name = ucwords($directory_name);
        if(!empty($directory_name)) {
            if(!in_array($directory_name, $current_user->role->directories)) {
                return abort(404);
            }else{
                $current_directory = Directory::where('name', $directory_name)->first()->id ?? '';
            }
        }

        if(!empty($current_directory)) {
            $current_directory = Directory::find($current_directory);
            $parents = collect($current_directory->parents())->reverse();
            $directories = Directory::where('parent_id', $current_directory->id)->where(function($q) use($current_user) {
                $q->where('user_id', $current_user->id)
                    ->orWhereNull('user_id');
            })->get();
            
            $files = File::where('directory_id', $current_directory->id)
                        ->where('user_id', $current_user->id)
                        ->get();
        }else {
            $directories = Directory::whereNull('parent_id');
            if($current_user->role->role_name !== 'Administrator') {
                $directories = $directories->whereIn('name', $current_user->role->directories);
            }
                    
            $directories = $directories->get();
        }
        
        return view('archives.index', compact('users', 'directories', 'current_directory', 'files', 'parents', 'current_user'));
    }

    public function search(Request $request)
    {
        $users = [];
        $current_user = !empty($request->userSearch) ? User::findOrFail($request->userSearch) : Auth::user();
        if(in_array(Auth::user()->role->role_name, config('app.manage_archive'))) {
           $current_user = !empty($request->userSearch) ? $current_user->id : '';
        }
        $fileSearch = $request->fileSearch;
        $files = File::where('file_name', 'LIKE', "%$request->fileSearch%");

        if(!empty($current_user)) {
            $files = $files->where(function($q) use($current_user){
                $q->where('user_id', $current_user->id);
            });
        }

        $files = $files->get();

        return view('archives.search', compact('users', 'files', 'current_user', 'fileSearch'));
    }

    public function sharedWithMe(Request $request)
    {
        $current_user = Auth::user();
        $users = User::get();

        $fileSearch = $request->fileSearch;
        $files = File::where(function($q) use($current_user){
                    $q->where('user_id', $current_user->id)
                        ->orWhereHas('file_users', function($q2) use($current_user){
                            $q2->where('user_id', $current_user->id);
                        });
                });
        if(!empty($fileSearch)) {
            $files = $files->where('file_name', 'LIKE', "%$fileSearch%");
        }

        $files = $files->get();
        
        return view('archives.shared', compact('users', 'files', 'current_user', 'fileSearch'));
    }

    public function storeDirectory(Request $request)
    {
        if(Directory::where('name', $request->directory)
            ->where('parent_id', $request->parent_directory)
            ->exists()){
                return back()->withError('Directory Already Exists!');
        }

        $user = Auth::user();
        if(in_array($user->role->role_name, config('app.manage_archive'))) {
           $user = null;
        }

        Directory::create([
            'parent_id' => $request->parent_directory ?? null,
            'name' => $request->directory,
            'user_id' => $user->id ?? null
        ]);

        return back()->withMessage('Directory created successfully');
    }

    public function updateDirectory(Request $request, $id)
    {
        $directory = Directory::findOrFail($id);

        if(Directory::where('name', $request->directory)
            ->where('parent_id', $request->parent_directory)
            ->where('id', '!=', $id)
            ->exists()){
                return back()->withError('Directory Already Exists!');
        }

        $directory->name = $request->directory;
        $directory->save();
        
        return back()->withMessage('Directory updated successfully');
    }

    public function deleteDirectory(Request $request, $id)
    {
        $directory = Directory::findOrFail($id);
        $user = Auth::user();
        if($directory->user_id !== $user->id && !in_array($user->role->role_name, config('app.manage_archive'))) {
            return back()->withError("You don't have permission to delete the directory");
        }

        $directory->files()->delete();
        $directory->delete();
        return back()->withMessage('Directory deleted successfully');
    }

    public function storeFile(Request $request)
    {
        $user = Auth::user();
        if(File::where('file_name', $request->file_name)
            ->where('directory_id', $request->parent_directory)
            ->where('user_id', $user->id)
            ->exists()){
                return back()->withError('File Already Exists!');
        }

        if ($request->hasFile('file_attachment')) {
            $now = Carbon::now();
            $file = $request->file('file_attachment');
            $hash_name = md5($file->getClientOriginalName() . uniqid());
            $target_path = sprintf('attachments/%s/%s/%s/%s', $now->year, $now->month, $now->day, $hash_name);
            $path = Storage::put($target_path, $file);
            $file_name = $request->file_name.".".$file->getClientOriginalExtension();

            File::create([
                'directory_id' => $request->parent_directory ?? null,
                'user_id' => $user->id,
                'file_name' => $file_name,
                'file_mime' => $file->getClientMimeType(),
                'container_path' => $path
            ]);
        }

        return back()->withMessage('File uploaded successfully');
    }

    public function downloadFile($id)
    {
        $file = File::findOrFail($id);
        $user = Auth::user();
        if($file->user_id !== $user->id && !in_array($user->role->role_name, config('app.manage_archive'))) {
            return back()->withError("You don't have permission to download the file");
        }

        $content = Storage::get($file->container_path);
        
        return response()->download(
            storage_path('app/'.$file->container_path), 
            $file->file_name, 
            ['Content-Type' => $file->file_mime]
        );
    }

    public function deleteFile(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $user = Auth::user();
        if($file->user_id !== $user->id && !in_array($user->role->role_name, config('app.manage_archive'))) {
            return back()->withError("You don't have permission to delete the file");
        }

        $file->delete();
        return back()->withMessage('File deleted successfully');
    }

    public function shareFile(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $user = Auth::user();
        if($file->user_id !== $user->id && !in_array($user->role->role_name, config('app.manage_archive'))) {
            return back()->withError("You don't have permission to share the file");
        }
        
        FileUser::where('file_id', $id)->delete(); // Remove exitsting
        if(!empty($request->userShare)) {
            foreach($request->userShare as $user) {
                FileUser::create([
                    'file_id' => $id,
                    'user_id' => $user
                ]);
            }
        }else {
           return back()->withMessage('File unshared successfully');
        }

        return back()->withMessage('File shared successfully');
    }
}
