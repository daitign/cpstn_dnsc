<?php

namespace App\Repositories;

use Storage;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\FileUser;
use App\Models\Directory;
use Illuminate\Support\Facades\Auth;

class DirectoryRepository {

    public function getDirectoryFiles($parent_directory = '')
    {
        $current_user = Auth::user();
        $users = $current_user->role->role_name == 'Administrator' ? User::get() : User::where('role_id', $current_user->role_id)->get();
        $parent_directory = $this->getDirectory($parent_directory);
        
        if(in_array($current_user->role->role_name, ['Process Owner', 'Document Control Custodian'])) {
            $directory = $this->getDirectory($current_user->assigned_area->area_name, $parent_directory->id);
        }else {
            $directory = $parent_directory;
            $parent_directory = null;
        }

        $directories = Directory::where('parent_id', $directory->id)->get();
        
        $files = File::where('directory_id', $directory->id)
                    ->where('user_id', $current_user->id)
                    ->get();
        return compact('files', 'current_user', 'users', 'directories', 'directory', 'parent_directory');
    }    

    public function getDirectory($name, $parent_id = null)
    {
        return Directory::firstOrcreate([
            'name' =>  $name,
            'parent_id' => $parent_id
        ]);
    }
}