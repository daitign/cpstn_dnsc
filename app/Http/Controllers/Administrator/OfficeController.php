<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function addOffice(Request $request)
    {
        $validatedData = $request->validate([
            'office_name' => 'required|unique:offices,office_name',
            'office_description' => 'required|unique:offices,office_description',
        ]);

        $office = new Office;
        $office->office_name = $request->office_name;
        $office->office_description = $request->office_description;
        $office->area_id = 1;
        $office->save();

        return redirect()->route('admin-area-page')->with('success', 'Office added successfully');
    }

    public function editOffice(Request $request)
    {
        $validatedData = $request->validate([
            'office_id'=>'required|exists:offices,id',
            'office_name' => 'required|unique:offices,office_name,'.$request->office_id,
            'office_description' => 'required|unique:offices,office_description,'.$request->office_id,
        ]);

        Office::where('id',$request->office_id)
        ->update($request->except('_token','_method','office_id'));

        return redirect()->route('admin-area-page')->with('success', 'Office updated successfully');
    }
}
