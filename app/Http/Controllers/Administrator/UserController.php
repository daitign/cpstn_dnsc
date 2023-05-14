<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Area;
use App\Models\User;
use App\Models\Role;
use App\Models\AreaUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $roles = Role::get();
        $request_role = $request->role ?? '';
        $users = User::with('role');
        if(!empty($request_role)) {
            $users = $users->whereHas('role', function($q) use($request_role){
                $q->where('role_name', $request_role);
            });
        }
        $users = $users->get();

        return view('administrators.user', compact('users', 'roles' ,'request_role'));
    }

    public function destroy(string $id)
    {
        User::where('id',$id)->delete();

        return redirect()->route('admin-pending-users-page')->with('success', 'User removed successfully');
    }

    public function pending()
    {
        $data = User::whereNull('role_id')->get();
        return view('administrators.pending',[
            'data' => $data,
            'data2'=>Role::get()
        ]);
    }

    public function approve(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::where('id',$request->user_id)
        ->withTrashed()
        ->update([
            'role_id'=>$request->role_id,
            'deleted_at'=>null
        ]);

        return redirect(URL::previous())->with('success', 'User approved successfully');
    }

    public function rejected()
    {
        $data = User::onlyTrashed()->get();
        return view('administrators.rejected',[
            'data' => $data,
            'data2'=>Role::get()
        ]);
    }
    

    public function assignUserList()
    {
        $all_areas = Area::get();
        $main_areas = Area::with(['children'])->whereNull('parent_area')->get();
        
        $areas = Area::with(['children'])->get();
        $data = User::query()
                ->whereHas('role', function($q){
                    $q->whereIn('role_name', ['Process Owner', 'Document Control Custodian']);
                })
                ->join('roles','roles.id','users.role_id')
                ->select('users.*','roles.role_name')
                ->get();
                
        return view('administrators.assign', compact('data', 'areas', 'main_areas'));
    }

    public function assignUser(Request $request)
    {
        $area_id = $request->assign_area;
        $area = Area::findOrFail($area_id);
        AreaUser::where('user_id', $request->user_id)->delete();
        
        AreaUser::firstOrCreate([
            'user_id' => $request->user_id,
            'area_id' => $area->id,
        ]);

        return redirect(URL::previous())->with('success', 'User has been assigned successfully');
    }
}
