<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\File;
use App\Models\Directory;

use App\Repositories\DirectoryRepository;

class DCCController extends Controller
{
    private $dr;

    public function __construct() 
    {
        $this->dr = new DirectoryRepository;
    }

    public function dashboard()
    {
        $data = (object) [
            'files' => File::where('user_id', Auth::user()->id)->count(),
        ];

        return view('Dcc.dashboard', compact('data'));
    }

    public function manuals()
    {
        $directories = Directory::where('area_id', Auth::user()->assigned_area->id)->get();
        foreach($directories as $key => $directory) {
            $directory->grand_parent = $this->dr->getGrandParent($directory);
        }
        $directory = $directories->whereIn('grand_parent', 'Manuals')->first();

        $data = $this->dr->getArchiveDirectoryaAndFiles($directory->id);
        $data['page_title'] = 'Manuals';
        return view('archives.index', $data);
    }

    public function evidences()
    {
        $directories = Directory::where('area_id', Auth::user()->assigned_area->id)->get();
        foreach($directories as $key => $directory) {
            $directory->grand_parent = $this->dr->getGrandParent($directory);
        }
        $directory = $directories->whereIn('grand_parent', 'Evidences')->first();

        $data = $this->dr->getArchiveDirectoryaAndFiles($directory->id);
        $data['page_title'] = 'Evidences';
        return view('archives.index', $data);
    }
}
