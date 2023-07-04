<?php

namespace App\Repositories;

use Storage;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\File;
use App\Models\User;
use App\Models\FileUser;
use App\Models\Directory;
use App\Models\AuditPlan;
use Illuminate\Support\Facades\Auth;

class DirectoryRepository {

    public function getDirectoriesAndFiles($directory_id = null, $user_id = null, $grand_parent = null)
    {
        $users = [];
        $files = [];
        $parents = [];
        $directories = [];
        $current_directory = [];
        $current_user = !empty($user_id) ? User::findOrFail($user_id) : Auth::user();
        $role = $current_user->role->role_name;

        if(empty($grand_parent)) {
            $directories = Directory::where('parent_id', null)->whereIn('name', $current_user->role->directories)->get();
        }else{
            $grand_parent = Directory::where('parent_id', null)->whereIn('name', $grand_parent)->get();
            $directories = Directory::where('parent_id', $grand_parent->id)->get();
        }
        if($directory_id) {
            $current_directory = Directory::where('id', $directory_id)->firstOrFail();
            if(!empty($grand_parent)) { // Make sure current directory is equal to grand parent
                if($grand_parent->name == $this->getGrandParent($directory)) {
                    return abort(404);
                }
            }

            $parents = $this->getRootDirectories($current_directory);
            krsort($parents);
            $parents[] = $current_directory->toArray();

            $directories = Directory::where('parent_id', $directory_id)->get();

            $files = $this->getDirectoryFiles($current_directory, $current_user);
        }

        // Check if directories are assign or parent of assigned
        $allowed_directories = [];
        foreach($directories as $directory) {
            if($this->allowedDirectory($directory, $current_user)) {
                $allowed_directories[] = $directory;
            }
        }
        $directories = $allowed_directories;

        return compact('users', 'directories', 'current_directory', 'files', 'parents', 'current_user');
    }

    public function getDirectoryFiles($current_directory,  $current_user) {
        $role_file_access = [
            'Internal Auditor', 
            'Internal Lead Auditor', 
            'Document Control Custodian',
            'College Management Team',
            'Quality Assurance Director'
        ];

        if(in_array($current_user->role->role_name, $role_file_access)){
            $files = File::where('directory_id', $current_directory->id)->get();
        }else{
            $files = File::where('directory_id', $current_directory->id)
                ->where(function($q) use($current_user, $current_directory) {
                    $q->where('user_id', $current_user->id);
                    if($current_user->role->role_name !== 'Staff') {
                        $q->orWhere('type', 'templates');
                    }
                })->get();
        }

        return $files;
    }

    public function allowedDirectory($directory, $current_user)
    {
        $allowed = true;
        if(in_array($current_user->role->role_name, config('app.role_with_assigned_area'))) {
            $assigned_areas = $current_user->assigned_areas->pluck('id')->toArray();
            if(!in_array($directory->area_id, $assigned_areas)) {
                $allowed = false;
                // Check from each child
                $this->getDirectoryChildBranches($directory, $assigned_areas, $allowed);

                if(!$allowed) {
                    $root_directories = $this->getRootDirectories($directory);
                    
                    foreach($root_directories as $root) {
                        if(in_array($root['area_id'], $assigned_areas)) {
                            $allowed = true;
                            break;
                        }
                    }
                }
            }
        }

        return $allowed;
    }
    
    public function getDirectoryChildBranches($directory, $assigned_areas = null, &$is_allowed = null)
    {
        $directories = [];
        if(!empty($directory->children)) {
            foreach($directory->children as $child) {
                if(!empty($child->children)) {
                    $child['branches'] = $this->getDirectoryChildBranches($child, $assigned_areas, $is_allowed);
                }
                if(!empty($assigned_areas) && in_array($child->area_id, $assigned_areas)) {
                    $is_allowed = true;
                }
                $directories[] = $child;
            }
        }
        return $directories;
    }

    public function getRootDirectories($directory)
    {
        $directories = [];
        if(!empty($directory->parent)) {
            $directories = [$directory->parent->toArray()];
            $directories = array_merge($directories, $this->getRootDirectories($directory->parent));
        }

        return $directories;
    }

    public function getDirectory($name, $parent_id = null, $area_id = null)
    {
        return Directory::firstOrcreate([
            'name' =>  $name,
            'parent_id' => $parent_id,
            'area_id' => $area_id
        ]);
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

    public function getGrandParentDirectory($directory)
    {
        if(!empty($directory->parent)) {
            return $this->getGrandParentDirectory($directory->parent);
        }else{
            return $directory;
        }
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

    public function getAreaFamilyTree($areas = null, $selectable_type = null, $selected_areas = []) {
        $areas = empty($areas) ? Area::whereNull('parent_area')->get() : $areas;
        return $this->getAreaGrandTree($areas, $selectable_type, $selected_areas);
    }

    private function getAreaGrandTree($areas, $selectable_type, $selected_areas)
    {
        $tree_areas = [];
        foreach($areas as $area) {
            $selectable =  !empty($area->parent_area);
            if(!empty($selectable_type)) {
               $selectable = $selectable_type == $area->type;
            }
            $tree_area = [
                'id' => $area->id,
                'text' => $area->area_name,
                'selectable' => $selectable,
                'state' => [
                    'selected' => in_array($area->id, $selected_areas),
                    'expanded' => in_array($area->id, $selected_areas),
                ]
            ];
            if(count($area->children) > 0) {
                $tree_area['nodes'] = $this->getAreaFamilyTree($area->children, $selectable_type, $selected_areas);
            }
            $tree_areas[] = $tree_area;
        }

        return $tree_areas;
    }

    public function getDirectoryByAreaAndGrandParent($area_id, $grandParent) {
        $directories = Directory::where('area_id', $area_id)->get();
        $directory = null;
        foreach($directories as $key => $dir) {
            if($this->getGrandParent($dir) == $grandParent) {
                $directory = $dir;
            }
        }
        return $directory;
    }
}