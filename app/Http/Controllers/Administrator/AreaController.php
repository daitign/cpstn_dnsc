<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

use App\Models\Area;
use App\Models\Directory;
use App\Repositories\DirectoryRepository;

class AreaController extends Controller
{
    public function __construct() 
    {
        $this->dr = new DirectoryRepository;
    }
    
    public function index()
    {
        $main_areas = Area::with(['children'])->whereNull('parent_area')->get();
        $areas = Area::with(['children'])->get();
        return view('administrators.area', compact('areas', 'main_areas'));
    }

    public function store(Request $request)
    {
        $parent_area = '';
        $area_type = $request->area_type;
        $area_name = ucwords($request->area_name);
        $area_description = ucwords($request->area_description);

        if($area_type == 'office') {
            $parent = Area::where('area_name', 'Administration')->firstOrFail();
        }elseif($area_type == 'institute') {
            $parent = Area::where('area_name', 'Academics')->firstOrFail();
        }else{
            $parent = Area::findOrFail($request->parent_area);
        }
        $parent_id = $parent->id;
        

        if(Area::where('area_name', $area_name)->where('parent_area', $parent_id)->exists()) {
            return redirect()->back()->with('error', ucfirst($area_type).' already exists');
        }

        // Create Folder
        if(in_array($area_type, ['process', 'program', 'institute', 'office'])) {
            
            $template_dir = $this->dr->getDirectory('Templates');
            if(in_array($area_type, ['process', 'program'])) {
                $directory = $this->dr->getDirectory('Process Owner', $template_dir->id);
            }else{
                $directory = $this->dr->getDirectory('Document Control Custodian', $template_dir->id);
            }

            // Create OR Get Last Parent Directory for Templates
            $root_areas = $this->dr->getAreaTree($parent);
            foreach($root_areas as $root_area) {
                $directory = $this->dr->getDirectory($root_area['area_name'], $directory->id, $root_area['id']);
            }
            $directory = $this->dr->getDirectory($parent->area_name, $directory->id, $parent->id);
        }
        $area = Area::create([
            'area_name' => $area_name,
            'area_description' => $area_description,
            'parent_area' => $parent_id,
            'type' => $area_type
        ]);

        $directory = $this->dr->getDirectory($area_name, $directory->id, $area->id);

        return redirect()->back()->with('success', ucfirst($area_type).' created successfully');
    }

    public function update(Request $request)
    {
        $id = $request->area_id;
        $area_name = ucwords($request->area_name);
        $area_description = ucwords($request->area_description);

        $area = Area::findOrFail($id);
        
        if(Area::where('area_name', $area_name)
            ->where('parent_area', $area->parent_area)
            ->where('id', '!=', $id)->exists()) 
        {
            return redirect()->back()->with('error', ucfirst($area_type).' already exists');
        }

        $area->area_name = $area_name;
        $area->area_description = $area_description;
        $area->save();

        $directories = Directory::where('area_id', $area->id)->get();
        foreach($directories as $directory) {
            $directory->name = $area_name;
            $directory->save();
        }

        
        return redirect()->back()->with('success', ucfirst($area->type).' updated successfully');
    }
}
