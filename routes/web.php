<?php

use App\Http\Controllers\Administrator\AreaController;
use App\Http\Controllers\Administrator\DashboardController;
use App\Http\Controllers\Administrator\InstituteController;
use App\Http\Controllers\Administrator\OfficeController;
use App\Http\Controllers\Administrator\ProcessController;
use App\Http\Controllers\Administrator\ProgramController;
use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\Administrator\SurveyController as AdminSurveyController;
use App\Http\Controllers\Administrator\UserController as AdminUserController;

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DCC\DCCDashboardController;
use App\Http\Controllers\DCC\DCCEvidenceController;
use App\Http\Controllers\DCC\DCCRemarkController;
use App\Http\Controllers\DCC\TemplateController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OfficeUserController;
use App\Http\Controllers\PO\PODashboardController;
use App\Http\Controllers\PO\POEvidenceController;
use App\Http\Controllers\ProcessUserController;
use App\Http\Controllers\ProgramUserController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffTemplateController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\HR\HRDashboardController;
use App\Http\Controllers\HR\SurveyController as HRSurveyController;
use App\Http\Controllers\HR\OfficeController as HROfficeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function(){
    Route::get('/',[AuthController::class,'loginPage'])->name('login-page');
    Route::post('login',[AuthController::class,'login'])->name('login');
    Route::prefix('surveys')->group(function(){
        Route::get('/',[SurveyController::class,'create'])->name('surveys.create');
        Route::post('/',[SurveyController::class,'store'])->name('surveys.store');
        Route::get('/success',[SurveyController::class,'success'])->name('surveys.success');
    });
});

Route::middleware(['auth'])->group(function(){
    Route::prefix('archives')->group(function() {
        Route::get('/',[ArchiveController::class,'index'])->name('archives-page');
        Route::post('/search',[ArchiveController::class,'search'])->name('archives-search');
        Route::post('/directory',[ArchiveController::class,'storeDirectory'])->name('archives-store-directory');
        Route::post('/directory/{id}/update',[ArchiveController::class,'updateDirectory'])->name('archives-update-directory');
        Route::delete('/directory/{id}/delete',[ArchiveController::class,'deleteDirectory'])->name('archives-delete-directory');
        
        Route::post('/file',[ArchiveController::class,'storeFile'])->name('archives-store-file');
        Route::get('/file/{id}',[ArchiveController::class,'downloadFile'])->name('archives-download-file');
        Route::delete('/file/{id}',[ArchiveController::class,'deleteFile'])->name('archives-delete-file');
    });


    // Admin routes
    Route::prefix('administrator')->middleware('admin')->group(function(){
        Route::get('/',function(){
            return redirect()->route('admin-dashboard-page');
        });
        Route::get('/dashboard',[DashboardController::class,'adminDashboardPage'])->name('admin-dashboard-page');
        Route::get('/area',[AreaController::class,'adminAreaPage'])->name('admin-area-page');
        Route::post('/add-institute',[InstituteController::class,'addInstitute'])->name('add-institute');
        Route::put('/edit-institute',[InstituteController::class,'editInstitute'])->name('edit-institute');
        Route::post('/add-program',[ProgramController::class,'addProgram'])->name('add-program');
        Route::put('/edit-program',[ProgramController::class,'editProgram'])->name('edit-program');
        Route::post('/add-process',[ProcessController::class,'addProcess'])->name('add-process');
        Route::put('/edit-process',[ProcessController::class,'editProcess'])->name('edit-process');
        Route::post('/add-office',[OfficeController::class,'addOffice'])->name('add-office');
        Route::put('/edit-office',[OfficeController::class,'editOffice'])->name('edit-office');
        Route::get('/pending-users',[UserController::class,'pending'])->name('admin-pending-users-page');
        Route::get('/rejected-users',[UserController::class,'rejected'])->name('admin-rejected-users-page');
        Route::get('/list-dcc-po',[UserController::class,'listDccPo'])->name('list-dcc-po');
        Route::put('/approve-user',[UserController::class,'approve'])->name('admin-approve-user');
        Route::post('/add-program-user',[ProgramUserController::class,'addProgramUser'])->name('add-program-user');
        Route::post('/add-office-user',[OfficeUserController::class,'addOfficeUser'])->name('add-office-user');
        Route::post('/add-process-user',[ProcessUserController::class,'addProcessUser'])->name('add-process-user');
        
        Route::get('/users',[AdminUserController::class,'index'])->name('admin-user-list');
        // Route::prefix('roles')->group(function(){
        //     Route::get('/',[RoleController::class,'index'])->name('admin-role-page');
        //     Route::get('{id}',[RoleController::class,'show'])->name('admin-user-list');
        // });
        
        Route::get('/surveys',[AdminSurveyController::class,'index'])->name('admin-surveys-list');
    });

    Route::prefix('dcc')->middleware('dcc')->group(function(){
        Route::get('/',function(){
            return redirect()->route('dcc-dashboard-page');
        });
        Route::get('/dashboard',[DCCDashboardController::class,'dashboard'])->name('dcc-dashboard-page');
        Route::get('/template',[TemplateController::class,'index'])->name('dcc-template-page');
        Route::get('/evidence',[DCCEvidenceController::class,'showProgramEvidence'])->name('dcc-show-evidence');
        Route::get('/evidence/office/{office}',[DCCEvidenceController::class,'showOfficeProcess'])->name('dcc-show-office-process');
        Route::get('/evidence/office/{office}/{process}',[DCCEvidenceController::class,'evidenceProcess'])->name('dcc-show-evidence-directory-office');
        Route::get('/evidence/office/{office}/{process}/{parent}',[DCCEvidenceController::class,'evidenceDirectories'])->name('dcc-show-evidence-directory-office-parent');
        Route::get('/evidence/program/{program}',[DCCEvidenceController::class,'showProgramProcess'])->name('dcc-show-program-process');
        Route::get('/evidence/program/{program}/{process}',[DCCEvidenceController::class,'evidenceProcess'])->name('dcc-show-evidence-directory-program');
        Route::get('/evidence/program/{program}/{process}/{parent}',[DCCEvidenceController::class,'evidenceDirectories'])->name('dcc-show-evidence-directory-program-parent');
        Route::post('/evidence-add-folder',[DCCEvidenceController::class,'addEvidenceFolder'])->name('dcc-add-folder-evidence');
        Route::post('/evidence-rename-folder',[DCCEvidenceController::class,'renameEvidenceFolder'])->name('dcc-rename-folder-evidence');
        Route::post('/evidence-remove-folder',[DCCEvidenceController::class,'removeEvidenceFolder'])->name('dcc-remove-folder-evidence');
    });

    Route::prefix('po')->middleware('po')->group(function(){
        Route::get('/',function(){
            return redirect()->route('po-dashboard-page');
        });
        Route::get('/dashboard',[PODashboardController::class,'dashboard'])->name('po-dashboard-page');
        Route::get('/evidence',[POEvidenceController::class,'index'])->name('po-evidence-page');
        Route::prefix('office')->group(function(){
            Route::get('/{office}',[POEvidenceController::class,'office_process'])->name('po-office-process');
            Route::get('/{office}/{process}',[POEvidenceController::class,'root_office_folder'])->name('po-office-root-process');
        });
        Route::prefix('program')->group(function(){
            Route::get('/{program}',[POEvidenceController::class,'program_process'])->name('po-program-process');
            Route::get('/{program}/{process}',[POEvidenceController::class,'root_program_folder'])->name('po-program-root-process');
            Route::get('/{program}/{process}/{parent}',[POEvidenceController::class,'parent_program_folder'])->name('po-program-parent-process');
        });
        Route::post('/upload-evidence',[POEvidenceController::class,'uploadEvidence'])->name('upload-evidence');
        Route::post('/update-evidence',[POEvidenceController::class,'updateName'])->name('rename-evidence');
        Route::post('/update-file-evidence',[POEvidenceController::class,'updateFile'])->name('update-file-evidence');
        Route::post('/delete-file-evidence/',[POEvidenceController::class,'deleteFile'])->name('delete-file-evidence');
    });

    Route::prefix('hr')->middleware('hr')->group(function(){
        Route::get('/',function(){
            return redirect()->route('hr-dashboard-page');
        });
        Route::get('/dashboard',[HRDashboardController::class,'dashboard'])->name('hr-dashboard-page');
        Route::get('/survey',[HRSurveyController::class,'index'])->name('hr-survey-page');

        Route::prefix('offices')->group(function(){
            Route::get('/',[HROfficeController::class,'index'])->name('hr-offices-page');
            Route::post('/',[HROfficeController::class,'store'])->name('hr-offices-create');
            Route::post('/{id}/update',[HROfficeController::class,'update'])->name('hr-offices-update');
            Route::delete('/{id}',[HROfficeController::class,'delete'])->name('hr-offices-delete');
        });
    });

    Route::middleware('staff')->prefix('staff')->group(function () {
        // Sidebar
        Route::get('/dashboard', [StaffDashboardController::class, 'dashboard'])->name('staff.dashboard');
        Route::get('/template', [StaffTemplateController::class, 'index'])->name('staff.template.index');
        
        // Template Part
        Route::get('/template/roles', [StaffTemplateController::class, 'allRoles'])->name('staff.template.roles');
        Route::get('/template/roles/{role}', [StaffTemplateController::class, 'roleTemplate'])->name('staff.template.roles.root');

        
        Route::get('/template/program', [StaffTemplateController::class, 'showProgramTemplate'])->name('staff.template.program');
        Route::get('/template/office/{office}', [StaffTemplateController::class, 'showOfficeProcess'])->name('staff.template.office.process');
        Route::get('/template/program/{program}', [StaffTemplateController::class, 'showProgramProcess'])->name('staff.template.program.process');
        Route::get('/template/process/{program}/{process}', [StaffTemplateController::class, 'templateProcess'])->name('staff.template.process');
        Route::get('/template/directories/{program}/{process}/{parent?}', [StaffTemplateController::class, 'templateDirectories'])->name('staff.template.directories');
        Route::post('/template/folder/add', [StaffTemplateController::class, 'addTemplateFolder'])->name('staff.template.folder.add');
        Route::post('/template/folder/rename/', [StaffTemplateController::class, 'renameTemplateFolder'])->name('staff.template.folder.rename');
        Route::post('/template/folder/remove', [StaffTemplateController::class, 'removeTemplateFolder'])->name('staff.template.folder.remove');
    });

    Route::post('add-remark',[DCCRemarkController::class,'addRemark'])->name('add-remark');
    Route::get('logout',[AuthController::class,'lg'])->name('logout');
    Route::get('download-evidence/{id}',[DownloadController::class,'evidenceDownload'])->name('download-evidence');
    
});

Route::resources([
    'users'=>UserController::class
]);
