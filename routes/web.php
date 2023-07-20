<?php

use App\Http\Controllers\AddPageController;
use App\Http\Controllers\AddRoleController;
use App\Http\Controllers\AddVideoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BlogToolController;
use App\Http\Controllers\CategoryToolController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClasssController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GuideToolController;
use App\Http\Controllers\Instructor;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\InstructorsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailToolController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\RecentActionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\viewclassController;

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/addInstructor', function () {
//     return inertia("addInstructor");
// })->name("addInstructor.view");
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });


// Route::get('/searchstd/{name?}', [viewclassController::class, 'searchstudent']);

Route::get("/activate/{code}", [AdminController::class, 'activate']);

Route::middleware([checkRole::class])->group(function () {

    Route::get('/login', function () {
        return inertia("Admin/AdLogin");
    })->withoutMiddleware([CheckRole::class]);
    Route::post('/login', [LoginController::class, 'store'])->name('login.store')->withoutMiddleware([CheckRole::class]);

    Route::get('/home', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::get('/', function () {
        return inertia('/dashboard');
    });



    Route::get('/dashboard', [DashboardController::class, "getdashboard"]);

    Route::get('/recentaction', [RecentActionController::class, 'getRecentAction']);

    Route::get('/classview/{id?}', [viewclassController::class, "getclassdata"])->name("class.view");
    // Route::get('/classsorting/{name?}', [viewclassController::class, "classsorting"])->name("class.sorting");
    Route::get('/classscategory/{id?}', [viewclassController::class, "classcategory"])->name("class.category");

    //Contact
    Route::resource('/contact', ContactController::class);

    Route::resource('/class', ClassController::class);


    Route::resource('/student', StudentController::class);
    Route::resource('/addvideo', AddVideoController::class);


    // Route::resource('/instructors', InstructorController::class);
    // Route::get('/instructors/{class?}{name?}', [Instructor::class, 'index'])->name("instructor.index");
    // Route::get('/instructors/edit/{id}', [InstructorController::class, 'show'])->name("instructors.show");
    // Route::get('/instructors/create', [InstructorController::class, 'create'])->name("instructors.create");
    Route::resource('/instructor', InstructorsController::class);

    Route::post('/setting/upload', [SettingController::class, 'upload'])->name("setting.upload");
    Route::post('/setting/upload_public', [SettingController::class, 'upload_public'])->name("setting.upload_public");
    // Route::get('/setting', function () {
    //     return inertia("SettingAdmin");
    // });
    Route::get('/setting', [SettingController::class, 'index'])->name("setting.index");


    // Route::resource('admin', AdminController::class);
    // Route::get('/addadmin',function(){
    //     return inertia("AddAdmin");
    // });
    // Route::get('/editadmin',function(){
    //     return inertia("EditAdmin");
    // });
    //Testing
    // Route::resource('/test', TestController::class);
    // Start Tools
    Route::resource('mailtool', MailToolController::class);
    Route::resource('privacypolicyTool', PrivacyPolicyController::class);
    Route::resource('categoryTool', CategoryToolController::class);
    Route::resource('guideTool', GuideToolController::class);
    Route::resource('blogTool', BlogToolController::class);
    // End Tools

    // Start Admin Permission
    Route::resource('adminPermission', AdminPermissionController::class);
    Route::resource('addRole', AddRoleController::class);
    Route::resource('pageList', AddPageController::class);
    // End Admin Permission
    //Start Admin
    Route::resource('admin', AdminController::class);

    //End Admin
    //Start Attendance
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('attendance/getStudent', [AttendanceController::class, 'getStudents'])->name("attendance.getstudent");
    Route::post('/attendance', [AttendanceController::class, 'store'])->name("attendance.create");;
    //End Attendance
    //Start Exam
    Route::resource('exam', ExamController::class);
    Route::get("/exam/entrymark/{id}", [ExamController::class, 'entryMark'])->name("exam.entrymark");
    Route::post('/exam/saveMark', [ExamController::class, "saveMark"])->name("exam.saveMark");
    //End Exam




    // Route::get('/contact/message/{id}',[ContactController::class])



});

Route::get('logout', function () {
    Auth::logout(); // logout user
    session()->flush();
    Redirect::back();

    return redirect('login');
});
