<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatevalidateRequest;
use App\Mail\StudentAccountCreate;
use App\Mail\StudentPasswordReset;
use App\Models\MSitemasterMyschool;
use App\Models\MSitemasterPublic;
use App\Models\MStudent;
use App\Models\TStudentAttendance;
use App\Models\TStudentClass;
use App\Models\TStudentExam;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

function generate()
{
    $char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!#$%&*+';

    $randString = "";

    for ($i = 0; $i < 8; $i++) {
        $index =  rand(0, strlen($char) - 1);
        $randString .= $char[$index];
    }

    return $randString;
}

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $search = $request->search;
        $checked = $request->selectedItem;

        // if($request.)
        // dd($checked);
        $model1 = new MStudent();
        $category = $model1->category();

        $tmpCheck = [];
        foreach ($category as $key => $value) {
            array_push($tmpCheck, $value->id);
        }

        // $data = ($request->selectedItem) ?
        //     $model1->allStuents(explode("-", $checked))
        //     : $model1->allStuents("", $search);
        if ($checked == "0")
            $data = $model1->allStuents([0]);
        else
            $data = ($request->selectedItem) ? $model1->allStuents(explode("-", $checked)) : $model1->allStuents("", $search);

        // dd($data);
        // typeOf($checked);
        return Inertia::render('Student', [
            'allStudents' => $data,
            'filter' => $search,
            'categories' => $category,
            'checkBox' => ($checked == "") ? $tmpCheck : explode("-", $checked),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $charLength = MStudent::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where('TABLE_SCHEMA', '=', env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", "users")
            ->where("CHARACTER_MAXIMUM_LENGTH", '!=', NULL)
            ->get();



        // dd($charLength);
        return inertia("AddStudent", ['charLength' => $charLength]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|min:4|max:30',
            'email' => "required|unique:users,email|email"
        ]);

        $siteModel = new MSitemasterMyschool();
        $siteData = $siteModel->idUpdateOrgData();


        $logo = $siteData[0]->logo;
        $sitename = $siteData[0]->sitename;
        $facebook_link = $siteData[0]->facebook_link;
        $youtube_link1 = $siteData[0]->youtube_link1;
        $youtube_link2 = $siteData[0]->youtube_link2;

        $createBy = session()->get('adminId');
        // dd($logo);
        $password = generate();
        $data = [
            'email' => $request->email,
            'password' => $password,
            'logo' => $logo,
            'sitename' => $sitename,
            'facebook_link' => $facebook_link,
            'youtube_link1' => $youtube_link1,
            'youtube_link2' => $youtube_link2
        ];

        // dd($password);


        try {
            Mail::to($request->email)->send(new StudentAccountCreate($data));
            $model = new MStudent();
            $model->studentAccount($request, $password, $createBy);
            return  redirect()->back()->with('mailsent', 'Mail Sent Successfully');
        } catch (Exception $e) {
            return  redirect()->back()->with('message', 'Mail Could Not Sent Successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // dd($id);
        $model = new MStudent();
        $studenProfile = $model->studnetDetailandClasses($id);

        $classes = new TStudentClass();
        $exam   = new TStudentExam();
        $attendances = new TStudentAttendance();

        $allUserRank = [];
        $allClass = [];

        $oneExamCallRank = [];

        //get ALl classid
        $allClassID = [];

        $oneClassRank = [];

        $oneClassEachExamRank = [];

        //get attendance
        $attendance  = $attendances->getAttendance($id);
        $examPercent = $exam->getExamRankPercent($id);

        //get Exam List
        $examList = $exam->getExamList();

        //get Class List
        $totalClass = $classes->totalClass($id);

        //loop for each examid
        foreach ($examList as  $examid) {
            //collect all data to array


            $allUserRank = array_merge($allUserRank, $exam->showRankTable($examid));
        }

        //loop for each classid rank
        foreach ($totalClass as $classid) {
            $oneClassEachExamRank = array_merge($oneClassEachExamRank, $exam->getExamlistByClassID($classid->class_id));
            $oneClassRank = array_merge($oneClassRank, $exam->getUserRankById($classid->class_id));
        }
        // $oneClassEachExamRank = array_merge($oneClassEachExamRank, $exam->getExamlistByClassID($id));
        // dd($oneClassEachExamRank);
        //filter for get only current login user id
        // dd($id);
        $examRank = array_filter($allUserRank, function ($rank) use ($id) {
            return ($rank->id == $id);
        });
        // dd($examRank);
        //filter for get only current login user id
        $eachExamRank = array_filter($oneClassEachExamRank, function ($rank) use ($id) {

            return ($rank->uid == $id);
        });


        $newArray = array_values($eachExamRank);


        for ($i = 0; $i < count($newArray); $i++) {
            if (!in_array($newArray[$i]->cid, $allClassID)) {
                array_push($allClassID, $newArray[$i]->cid);
            }
        }


        for ($i = 0; $i < count($allClassID); $i++) {
            $temp = [];
            for ($j = 0; $j < count($newArray); $j++) {
                if ($allClassID[$i] == $newArray[$j]->cid) {
                    array_push($temp, $newArray[$j]);
                }
            }

            array_push($oneExamCallRank, $temp);
        }

        //get all user rank
        $userRanks = $exam->getUserRank();

        //filter for get only current login user id
        $userRank = array_filter($userRanks, function ($ranking) use ($id) {
            return ($ranking->id == $id);
        });

        // dd($userRank);

        //filter for get overall rank only current login user id
        $overallRank = array_filter($oneClassRank, function ($ranking) use ($id) {
            return ($ranking->id == $id);
        });

        foreach ($totalClass  as $class) {
            array_push($allClass, $class->id);
        }

        $classid =  join(',', $allClass);

        $eachClass =   $classes->totalStudents($classid);


        return inertia('StudentView', [
            'studenProfile' => $studenProfile,
            'classes' => $totalClass,
            'attendance' => $attendance,
            'examRanks' => $examRank,
            'rank_mark' => $userRank,
            'all_ranks' => $userRanks,
            'one_class' => $eachClass,
            'exam_percent' =>  $examPercent,
            'overall_rank' => $overallRank,
            'class_rank'  => $oneExamCallRank
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $students = User::select('*')->where('id', $id)->get();


        // dd($students);
        return inertia("StudentEdit", ['students' => $students]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // dd($request->name);
        $update = User::find($id);
        $update->name = $request->name;
        $update->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = User::find($id);
        // dd($user->email);
        $siteModel = new MSitemasterMyschool();
        $siteData = $siteModel->idUpdateOrgData();

        $password = generate();
        $logo = $siteData[0]->logo;
        $sitename = $siteData[0]->sitename;
        $facebook_link = $siteData[0]->facebook_link;
        $youtube_link1 = $siteData[0]->youtube_link1;
        $youtube_link2 = $siteData[0]->youtube_link2;
        $data = [
            'email' => $user->email,
            'password' => $password,
            'logo' => $logo,
            'sitename' => $sitename,
            'facebook_link' => $facebook_link,
            'youtube_link1' => $youtube_link1,
            'youtube_link2' => $youtube_link2
        ];

        try {
            Mail::to($user->email)->send(new StudentPasswordReset($data));
 		$user->password=Hash::make($password);           
 		$user->reset = 1;
            $user->save();
            return  redirect()->back()->with('mailsent', 'Mail Sent Successfully');
        } catch (Exception $e) {
            return  redirect()->back()->with('message', 'Mail Could Not Sent Successfully');
        }
    }
}
