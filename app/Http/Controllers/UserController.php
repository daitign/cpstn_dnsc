<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\ProcessUser;
use App\Models\Role;
use App\Models\User;
use App\Models\File;
use App\Models\FileRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $data = (object) [
            'files' => File::where('user_id', Auth::user()->id)->count(),
        ];

        return view('user.dashboard', compact('data'));
    }

    public function __construct()
    {
        $this->middleware('auth')->except(['create','store']);
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
            'img'=>['nullable', 'file','mimes:jpg,jpeg,png','max:10000']
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $file_name = Uuid::uuid4()->toString();

        if($request->hasFile('img')) {
            $path = Storage::putFileAs('public/profiles',$request->file('img'),$file_name.'.'.$request->file('img')->extension());
            $validatedData['img'] = $path;
        }

        User::create($validatedData);

        return redirect()->route('login-page')->with('success', 'Account has been registered successfully');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function saveRemarks(Request $request, $file_id)
    {
        $file = File::findOrFail($file_id);

        if(!in_array(Auth::user()->role->role_name, [
            'Staff',
            'Internal Lead Auditor',
            'Internal Auditor'
        ])){
            return redirect()->back()->with('error', 'You are not authorized');
        }

        FileRemark::updateOrCreate(
            ['type' => $request->type, 'comments' => $request->comments],
            ['file_id' => $file_id, 'user_id' => Auth::user()->id]
        );
        
        return redirect()->back()->with('success', 'Your remarks has been saved successfully');
    }
}