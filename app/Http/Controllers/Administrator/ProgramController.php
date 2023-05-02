<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function addProgram(Request $request)
    {
        $validatedData = $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'program_name' => 'required|unique:programs,program_name',
            'program_description' => 'required|unique:programs,program_description',
        ]);

        Program::create($validatedData);

        return redirect()->route('admin-area-page')->with('success', 'Program added successfully');
    }

    public function editProgram(Request $request)
    {
        $validatedData = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'program_name' => 'required|unique:programs,program_name,'.$request->program_id,
            'program_description' => 'required|unique:programs,program_description,'.$request->program_id,
        ]);

        Program::where('id',$request->program_id)
        ->update($request->except('_token','_method','program_id'));

        return redirect()->route('admin-area-page')->with('success', 'Program updated successfully');
    }
}
