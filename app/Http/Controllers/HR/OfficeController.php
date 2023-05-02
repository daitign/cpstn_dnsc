<?php

namespace App\Http\Controllers\HR;

use App\Models\Directory;
use App\Models\Office;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $offices = Office::get();
        return view('HR.offices', compact('offices'));
    }

    public function store(Request $request)
    {
        Office::create([
            'office_name' => $request->office_name,
            'office_description' => $request->office_description,
            'area_id' => 1,
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
        $office = Office::find($id);
        $office->office_name = $request->office_name;
        $office->office_description = $request->office_description;
        $office->save();

        return redirect()->route('hr-offices-page')->with('success', 'Office updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $office = Office::find($id);
        $office->delete();

        return redirect()->route('hr-offices-page')->with('success', 'Office deleted successfully');
    }
}
