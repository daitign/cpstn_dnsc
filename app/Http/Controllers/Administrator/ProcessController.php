<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Rules\NoSameProcessInOffice;
use App\Rules\NoSameProcessInProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProcessController extends Controller
{
    public function addProcess(Request $request)
    {
        $listtype = $request->validate([
            'process_type' => ['required','string',Rule::in(['office','program'])]
        ]);

        if ($listtype['process_type'] == 'program') {
            $validatedData = $request->validate([
                'program_id' => 'required|exists:programs,id',
                'process_name' => ['required',new NoSameProcessInProgram($request->program_id)],
                'process_description' => 'required',
            ]);
        }
        else{
            $validatedData = $request->validate([
                'office_id' => 'required|exists:offices,id',
                'process_name' => ['required',new NoSameProcessInOffice($request->office_id)],
                'process_description' => 'required',
            ]);
        }

        Process::create($validatedData);

        return redirect()->route('admin-area-page')->with('success', 'Process added successfully');
    }

    public function editProcess(Request $request)
    {
        $validatedData = $request->validate([
            'process_id' => 'required|exists:processes,id',
            'process_name' => 'required|unique:processes,process_name,'.$request->process_id,
            'process_description' => 'required|unique:processes,process_description,'.$request->process_id,
        ]);

        Process::where('id',$request->process_id)
        ->update($request->except('_token','_method','process_id'));

        return redirect()->route('admin-area-page')->with('success', 'Process updated successfully');
    }
}
