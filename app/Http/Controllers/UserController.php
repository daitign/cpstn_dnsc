<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\ProcessUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create','store']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname'=>['required','max:255'],
            'middlename'=>['nullable','max:255'],
            'surname'=>['required','max:255'],
            'suffix'=>['nullable','max:255'],
            'username'=>['required','max:255','unique:users,username'],
            'password'=>['required','confirmed','max:255'],
            'img'=>['required','file','mimes:jpg,jpeg,png','max:10000']
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $file_name = Uuid::uuid4()->toString();
        $path = Storage::putFileAs('public/profiles',$request->file('img'),$file_name.'.'.$request->file('img')->extension());
        $validatedData['img'] = $path;

        User::create($validatedData);

        return redirect()->route('login-page')->with('success', 'Account has been registered successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
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
    

    public function listDccPo()
    {
        $data = User::query()
        ->whereIn('role_id',[10,3])
        ->join('roles','roles.id','users.role_id')
        ->select('users.*','roles.role_name')
        ->get();
        return view('administrators.assign',[
            'data'=>$data,
            'areas'=>Area::with(['institutes.programs.processes', 'offices.processes'])->get()
        ]);
    }
}
