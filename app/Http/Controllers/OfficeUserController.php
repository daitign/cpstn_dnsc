<?php

namespace App\Http\Controllers;

use App\Models\OfficeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class OfficeUserController extends Controller
{
    public function addOfficeUser(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'office_id' => 'required|exists:offices,id',
        ]);
        $check = OfficeUser::where('user_id',$request->user_id)
        ->where('office_id',$request->office_id)
        ->count();

        if ($check) {
            return back()->withErrors([
                'username' => 'User has been already assigned to that program',
            ]);
        }
        else{
            OfficeUser::insert($validatedData);
            return redirect(URL::previous())->with('success', 'User has been assigned successfully');
        }
        
    }
}
