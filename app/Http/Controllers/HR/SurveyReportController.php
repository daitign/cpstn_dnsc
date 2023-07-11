<?php

namespace App\Http\Controllers\HR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Storage;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\File;
use App\Models\Facility;
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
        $data = $this->dr->getDirectoriesAndFiles($this->parent, $request->directory ?? null);
        $data['route'] = \Str::slug($this->parent);
        $data['page_title'] = $this->parent;
        
        return view('archives.index', $data);
    }

    public function create()
    {
        $offices = Facility::get();
        return view('HR.survey_reports.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $parent_directory = $this->dr->getDirectory($this->parent, null);

        $facility = Facility::findOrFail($request->facility);
        $survey = $this->dr->getDirectory($facility->name, $parent_directory->id, null);
        
        $year = Carbon::parse($request->date)->format('Y');
        $directory = $this->dr->getDirectory($year, $survey->id);

        $file_id = null;
        if ($request->hasFile('file_attachments')) {
            $file = $this->dr->storeFile(
                        $request->name, 
                        $request->description, 
                        $request->file('file_attachments'), 
                        $directory->id, 
                        'survey_reports'
            );
            $file_id = $file->id;
        }

        SurveyReport::create([
            'name' => $request->name,
            'facility_id' => $facility->id,
            'description' => $request->description,
            'user_id' => $user->id,
            'directory_id' => $directory->id,
            'date' => $request->date,
            'file_id' => $file_id
        ]);

        $users = User::whereHas('role', function($q){ $q->where('role_name', \Roles::QUALITY_ASSURANCE_DIRECTOR); })->get();
        \Notification::notify($users, 'Submitted Survey Reports', route('admin-survey-reports'));

        return back()->withMessage('Survey Report created successfully');
    }
}
