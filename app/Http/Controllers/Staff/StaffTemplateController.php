<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Process;
use App\Models\Program;
use App\Models\Role;
use App\Models\Template;
use App\Rules\NoSameTemplateFolder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffTemplateController extends Controller
{
    public function index()
    {
        return view('staff.template.index');
        // $programs = Program::get();
        // $offices = Office::get();
        // $roles = Role::get();

        // return view('staff.template.program',[
        //     'programs'=>$programs,
        //     'offices'=>$offices,
        //     'roles'=>$roles
        // ]);
    }

    // Roles
    public function allRoles()
    {
        $roles = Role::get();
        return view('staff.template.roles.roles',[
            'roles'=>$roles
        ]);
    }

    public function roleTemplate($role)
    {
        $role = Role::findOrFail($role);

        $folders = Template::query()
        ->with(['directory'])
        ->where('role_id',$role->id)
        ->whereNull('directory_id')
        ->whereNull('template_id')
        ->get();

        $files = Template::query()
        ->with(['directory'])
        ->where('role_id',$role->id)
        ->whereNotNull('directory_id')
        ->whereNull('template_id')
        ->get();

        return view('staff.template.roles.root',[
            'folders'=>$folders,
            'files'=>$files,
            'role'=>$role
        ]);
    }





    public function showOfficeProcess($office)
    {
        $processes = Process::query()
            ->whereRaw('office_id = (SELECT id FROM offices WHERE office_name = ?)', [$office])
            ->get();
        return view('staff.template.process', [
            'processes' => $processes,
            'office' => $office
        ]);
    }

    public function showProgramProcess($program)
    {
        $processes = Process::query()
            ->whereRaw('program_id = (SELECT id FROM templates WHERE name = ?)', [$program])
            ->get();
        return view('staff.template.process', [
            'processes' => $processes,
            'program' => $program
        ]);
    }

    public function templateProcess($program,$process)
    {
        $templates = Template::query()
        ->where('process_id',$process)
        ->whereNull('template_id')
        ->get();

        $process_name = Process::query()
        ->where('id',$process)
        ->value('process_name');

        return view('staff.template.directory',[
            'templates'=>$templates,
            'process'=>$process_name
        ]);
    }

    public function templateDirectories($program,$process,$parent)
    {
        $templates = Template::query()
        ->with(['directory','template_remarks.user'])
        ->where('template_id',$parent)
        ->get();

        $process_name = Process::query()
        ->where('id',$process)
        ->value('process_name');

        $breadcrumbList = $this->getFolderName(array(),$parent);

        $breadcrumbList = array_reverse($breadcrumbList,true);

        return view('staff.template.directory',[
            'templates'=>$templates,
            'process'=>$process_name,
            'folders'=>$breadcrumbList
        ]);
    }

    public function getFolderName(array $arr, int $id)
    {
        $template = Template::query()
            ->select('id', 'folder_name', 'template_id')
            ->where('id', $id)
            ->first();
        $arr[$template['id']] = $template['folder_name'];
        if (!$template['template_id']) {
            return $arr;
        } else {
            return $this->getFolderName($arr, $template['template_id']);
        }
    }

    public function addTemplateFolder(Request $request)
    {
        $validatedData = $request->validate([
            'belong'=>['required',Rule::in('role','program','process')],
            'parent'=>['nullable','exists:template,id'],
            'type_id'=>['required'],
            'folder'=>['required','string','max:255',new NoSameTemplateFolder($request->parent)],
        ]);

        $template = new Template;
        if ($validatedData['belong'] == 'role') {
            $type = $request->validate([
                'type_id'=>['exists:roles,id']
            ]);
            $template->role_id = $type['type_id'];
        }
        else if($validatedData['belong'] == 'program'){
            $type = $request->validate([
                'type_id'=>['exists:programs,id']
            ]);
            $template->program_id = $type['type_id'];
        }
        else if($validatedData['belong'] == 'process'){
            $type = $request->validate([
                'type_id'=>['exists:processes,id']
            ]);
            $template->process_id = $type['type_id'];
        }

        $template->folder_name = $validatedData['folder'];
        $template->user_id = auth()->id();

        $template->save();
        
        return redirect()->back()->with('success','Folder successfully added');
    }

    public function renameTemplateFolder(Request $request)
    {
        $validatedData = $request->validate([
            'id'=>['required','exists:templates,id'],
            'folder'=>['required','string','max:255',new NoSameTemplateFolder(request()->route('parent'))],
        ]);

        Template::query()
        ->where('id',$validatedData['id'])
        ->update([
            'folder_name'=>$validatedData['folder']
        ]);

        return redirect()->back()->with('success','Folder successfully renamed!');
    }

    public function removeTemplateFolder(Request $request)
    {
        $validatedData = $request->validate([
            'id'=>['required','exists:templates,id'],
        ]);
        Template::query()
        ->where('id',$validatedData['id'])
        ->delete();
        return redirect()->back()->with('success','Folder successfully removed!');
    }



}
