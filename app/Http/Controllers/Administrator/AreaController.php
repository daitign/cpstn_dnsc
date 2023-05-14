<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

use App\Models\Area;
use App\Models\Directory;

class AreaController extends Controller
{
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
            $parent_id = Area::where('area_name', 'Administration')->firstOrFail()->id;
        }elseif($area_type == 'institute') {
            $parent_id = Area::where('area_name', 'Academics')->firstOrFail()->id;
        }else{
            $parent_id = Area::findOrFail($request->parent_area)->id;
        }

        if(Area::where('area_name', $area_name)->where('parent_area', $parent_id)->exists()) {
           return redirect()->back()->with('error', ucfirst($area_type).' already exists');
        }

        $area = Area::create([
            'area_name' => $area_name,
            'area_description' => $area_description,
            'parent_area' => $parent_id,
            'type' => $area_type
        ]);

        $directories = Directory::where('area_id', $parent_id)->get();
        foreach($directories as $directory) {
            Directory::create([
                'name' => $area_name,
                'parent_id' => $directory->id,
                'area_id' => $area->id
            ]);
        }

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
