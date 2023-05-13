<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Area;
use App\Models\Directory;

use App\Repositories\DirectoryRepository;

class OfficeController extends Controller
{
    private $dr;

    public function __construct() 
    {
        $this->dr = new DirectoryRepository;

    }

    public function index(Request $request)
    {
        $offices = Area::offices()->get();
        return view('HR.offices', compact('offices'));
    }

    public function store(Request $request)
    {
        $office_area = Area::where('name', 'Administration')->whereNull('parent_id')->firstOrFail();
        Area::create([
            'area_name' => $request->office_name,
            'area_description' => $request->office_description,
            'parent_area' => $office_area->id,
        ]);

        $directories = Directory::where('name', 'Administration')->whereNotNull('area_dependent')->get();
        foreach($directories as $directory) {
            Directory::create([
                'name' => $request->office_name,
                'parent_id' => $directory->id
            ]);
        }

        return redirect()->route('hr-offices-page')->with('success', 'Office created successfully');
    }

    public function update(Request $request, $id)
    {
        $office = Area::offices()->find($id);
        $office->office_name = $request->office_name;
        $office->office_description = $request->office_description;
        $office->save();

        return redirect()->route('hr-offices-page')->with('success', 'Office updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $office = Area::offices()->find($id);
        $office->delete();

        return redirect()->route('hr-offices-page')->with('success', 'Office deleted successfully');
    }
}
