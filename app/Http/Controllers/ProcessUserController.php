<?php

namespace App\Http\Controllers;

use App\Models\ProcessUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ProcessUserController extends Controller
{
    public function addProcessUser(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'process_id' => 'required|exists:processes,id',
        ]);
        $check = ProcessUser::where('user_id',$request->user_id)
        ->where('process_id',$request->process_id)
        ->count();

        if ($check) {
            return back()->withErrors([
                'username' => 'User has been already assigned to that process',
            ]);
        }
        else{
            ProcessUser::insert($validatedData);
            return redirect(URL::previous())->with('success', 'User has been assigned successfully');
        }
        
    }
}
