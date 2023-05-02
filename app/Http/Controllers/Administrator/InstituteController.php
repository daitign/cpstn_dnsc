<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function addInstitute(Request $request)
    {
        $validatedData = $request->validate([
            'institute_name' => 'required|unique:institutes,institute_name',
            'institute_description' => 'required|unique:institutes,institute_description',
        ]);

        $institute = new Institute;
        $institute->institute_name = $request->institute_name;
        $institute->institute_description = $request->institute_description;
        $institute->area_id = 2;
        $institute->save();

        return redirect()->route('admin-area-page')->with('success', 'Institute added successfully');
    }

    public function editInstitute(Request $request)
    {
        $validatedData = $request->validate([
            'institute_id'=>'required|exists:institutes,id',
            'institute_name' => 'required|unique:institutes,institute_name,'.$request->institute_id,
            'institute_description' => 'required|unique:institutes,institute_description,'.$request->institute_id,
        ]);

        Institute::where('id',$request->institute_id)
        ->update($request->except('_token','_method','institute_id'));

        return redirect()->route('admin-area-page')->with('success', 'Institute updated successfully');
    }
}