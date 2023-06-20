<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Facility;
use App\Models\Directory;

class OfficeController extends Controller
{

    public function index(Request $request)
    {
        $offices = Facility::get();
        return view('HR.offices', compact('offices'));
    }

    public function store(Request $request)
    {
        $area = Facility::create([
            'name' => $request->office_name,
            'description' => $request->office_description
        ]);

        return redirect()->route('hr-offices-page')->with('success', 'Office created successfully');
    }

    public function update(Request $request, $id)
    {
        $office = Facility::findOrFail($id);
        $office->area_name = $request->office_name;
        $office->area_description = $request->office_description;
        $office->save();

        return redirect()->route('hr-offices-page')->with('success', 'Office updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $office = Facility::find($id);
        $office->delete();

        return redirect()->route('hr-offices-page')->with('success', 'Office deleted successfully');
    }
}
