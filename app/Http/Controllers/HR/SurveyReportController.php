<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Storage;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Area;
use App\Models\User;
use App\Models\File;
use App\Models\Directory;
use App\Models\SurveyReport;
use Illuminate\Support\Facades\Auth;
use App\Repositories\DirectoryRepository;

class SurveyReportController extends Controller
{
    private $parent = 'Survey Reports';
    private $dr;

    public function __construct() 
    {
        $this->dr = new DirectoryRepository;

    }
    
    public function index(Request $request)
    {
        $data = $this->dr->getDirectoryFiles($this->parent);
        return view('archives.files', $data);
    }

    public function create()
    {
        $offices = Area::offices()->get();
        return view('HR.survey_reports.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $parent_directory = $this->dr->getDirectory($this->parent, null);

        $area = Area::findOrFail($request->area);
        $survey = $this->dr->getDirectory($area->area_name, $parent_directory->id, $area->id);
        
        $year = Carbon::parse($request->date)->format('Y');
        $directory = $this->dr->getDirectory($year, $survey->id);

        $file_id = null;
        if ($request->hasFile('file_attachment')) {
            $now = Carbon::now();
            $file = $request->file('file_attachment');
            $hash_name = md5($file->getClientOriginalName() . uniqid());
            $target_path = sprintf('attachments/%s/%s/%s/%s', $now->year, $now->month, $now->day, $hash_name);
            $path = Storage::put($target_path, $file);
            $file_name = $request->name.".".$file->getClientOriginalExtension();

            $file = File::create([
                'directory_id' => $directory->id,
                'user_id' => $user->id,
                'file_name' => $file_name,
                'file_mime' => $file->getClientMimeType(),
                'container_path' => $path,
                'description' => $request->description
            ]);

            $file_id = $file->id;
        }

        SurveyReport::create([
            'name' => $request->name,
            'area_id' => $area->id,
            'description' => $request->description,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);

        
        return back()->withMessage('Survey Report created successfully');
    }
}
