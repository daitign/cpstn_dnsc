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
            if(empty($current_user->assigned_area->area_name)) {
                return 'unassigned';
            };
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