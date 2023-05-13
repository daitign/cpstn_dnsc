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
            'img'=>['file','mimes:jpg,jpeg,png','max:10000']
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
}
