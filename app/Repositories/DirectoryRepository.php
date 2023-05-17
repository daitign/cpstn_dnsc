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

    public function getArchiveDirectoryaAndFiles($request_directory = null, $request_user = null, $directory_name = '')
    {
        $users = [];
        $files = [];
        $parents = [];
        $directories = [];
        $current_directory = $request_directory;

        $current_user = !empty($request_user) ? User::findOrFail($request_user) : Auth::user();
        $users = $current_user->role->role_name == 'Administrator' ? User::whereHas('role', function($q) { $q->where('role_name', '!=', 'Administrator'); })->get() : User::where('role_id', $current_user->role_id)->get();

        if(!empty($current_directory)) {
            $current_directory = Directory::find($current_directory);
            $parents = collect($current_directory->parents())->reverse();
            $directories = Directory::where('parent_id', $current_directory->id)->get();

            if(($current_user->role->role_name == 'Administrator' && $current_user->id == Auth::user()->id) ||
                ($current_user->role->role_name == 'Staff' && $this->getGrandParent($current_directory) == 'Manuals')
            ) {
                $files = File::where('directory_id', $current_directory->id)
                            ->get();
            }else{
                $files = File::where('directory_id', $current_directory->id)
                            ->where('user_id', $current_user->id)
                            ->get();
            }
        }else {
            if($current_user->role->role_name !== 'Administrator') {
                if(in_array($current_user->role->role_name, ['Document Control Custodian', 'Process Owner'])) {
                    $directories = Directory::where('area_id', $current_user->assigned_area->id);
                    $directories = $directories->get();
                    foreach($directories as $key => $directory) {
                        $directory->name = $this->getGrandParent($directory);
                    }

                    if(!empty($directory_name)) {
                        $directories = $directories->where('name', $directory_name);
                    }else{
                        $directories = $directories->whereIn('name', $current_user->role->directories);
                    }
                }else{
                    $directories = Directory::whereNull('parent_id');
                    $directories = $directories->whereIn('name', $current_user->role->directories);
                    $directories = $directories->get();
                }
            }else{
                $directories = Directory::whereNull('parent_id')->get();
            }
        }

        return compact('users', 'directories', 'current_directory', 'files', 'parents', 'current_user');
    }

    public function getDirectoryFiles($parent_directory = '')
    {
        $current_user = Auth::user();
        $users = $current_user->role->role_name == 'Administrator' ? User::get() : User::where('role_id', $current_user->role_id)->get();
        
        if(in_array($current_user->role->role_name, ['Process Owner', 'Document Control Custodian'])) {
            $directories = Directory::where('area_id', Auth::user()->assigned_area->id)->get();
            foreach($directories as $key => $directory) {
                $directory->grand_parent = $this->getGrandParent($directory);
            }
            $directory = $directories->whereIn('grand_parent', $parent_directory)->first();
            $parent_directory = $directory->parent;
        } else {
            $parent_directory = null;
            $directory = $parent_directory;
        }
        
        $directories = Directory::where('parent_id', $directory->id)->get();
        
        $files = File::where('directory_id', $directory->id)
                    ->where('user_id', $current_user->id)
                    ->get();
        return compact('files', 'current_user', 'users', 'directories', 'directory', 'parent_directory');
    }    

    public function getDirectory($name, $parent_id = null, $area_id = null)
    {
        return Directory::firstOrcreate([
            'name' =>  $name,
            'parent_id' => $parent_id,
            'area_id' => $area_id
        ]);
    }

    public function getDirectoryAssignedByGrandParent($grand_parent_name)
    {
        $directories = Directory::where('area_id', Auth::user()->assigned_area->id)->get();
        foreach($directories as $key => $directory) {
            $directory->grand_parent = $this->getGrandParent($directory);
        }
        $directory = $directories->whereIn('grand_parent', $grand_parent_name)->first();
        return Directory::where('parent_id', $directory->id)->get();
    }

    public function getAreaTree($area)
    {
        $areas = $this->getParentArea($area);
        krsort($areas);

        return $areas;
    }

    public function getParentArea($area)
    {
        $areas = [];
        if(!empty($area->parent)) {
            $areas = [$area->parent->toArray()];
            $areas = array_merge($areas, $this->getParentArea($area->parent));
        }

        return $areas;
    }

    public function makeDirectory($area, $parent_directory)
    {
        $parents = $this->getAreaTree($area);
        $last_parent = $parent_directory;
        foreach($parents as $parent) {
            $parent = $this->getDirectory($parent['area_name'], $last_parent, $parent['id']);
            $last_parent = $parent['id'];
        }

        return $this->getDirectory($area->area_name, $last_parent, $area->id);
    }

    public function getGrandParent($directory)
    {
        if(!empty($directory->parent)) {
            return $this->getGrandParent($directory->parent);
        }else{
            return $directory->name;
        }
    }

    public function getAreaFamilyTree($areas) {
        $tree_areas = [];
        foreach($areas as $area) {
            $tree_area = [
                'id' => $area->id,
                'text' => $area->area_name,
                'selectable' => !empty($area->parent_area)
            ];
            if(count($area->children) > 0) {
                $tree_area['nodes'] = $this->getAreaFamilyTree($area->children);
            }
            $tree_areas[] = $tree_area;
        }

        return $tree_areas;
    }
}