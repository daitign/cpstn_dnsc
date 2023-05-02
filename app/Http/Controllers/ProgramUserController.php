<?php

namespace App\Http\Controllers;

use App\Models\ProgramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ProgramUserController extends Controller
{
    public function addProgramUser(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
        ]);
        $check = ProgramUser::where('user_id',$request->user_id)
        ->where('program_id',$request->program_id)
        ->count();

        if ($check) {
            return back()->withErrors([
                'username' => 'User has been already assigned to that program',
            ]);
        }
        else{
            ProgramUser::insert($validatedData);
            return redirect(URL::previous())->with('success', 'User has been assigned successfully');
        }
        
    }
}
