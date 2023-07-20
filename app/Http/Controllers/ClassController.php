<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminValidation;
use App\Models\MClass;
use App\Models\TStudentClass;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Nette\Utils\Strings;
use PhpParser\Node\Expr\Cast\String_;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checked = $request->selectedItem;
        $class = new MClass();
        $category = $class->category();

        $checkedcat = [];
        foreach ($category as $key => $value) {
            array_push($checkedcat, $value->id);
        }

        if ($checked == "0")
            $classdata = $class->get_class([0]);
        else
            $classdata = ($request->selectedItem) ? $class->get_class(explode("-", $checked)) : $class->get_class("");

        return inertia("Class", ['dclass' => $classdata, 'categories' => $category, 'checkedcategories' => ($checked == "") ? $checkedcat : explode("-", $checked),]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $srcname = $request->searchstdname;
        $class = new MClass();
        $instructors = $class->get_instructors();
        $categories = $class->get_category();
        $students = ($request->searchstdname) ? $class->get_student($srcname) : $class->get_student("");

        return Inertia("AddClass", ['instructors' => $instructors, 'category' => $categories, 'student' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // AdminValidation
    public function store(AdminValidation $request)
    {
        $class = new MClass();
        $date1 = $request->input('day1');
        $date2 = $request->input('day2');
        $date3 = $request->input('day3');
        $date4 = $request->input('day4');
        $date5 = $request->input('day5');
        $date6 = $request->input('day6');
        $date7 = $request->input('day7');
        $studentids = $request->input('students');
        $img = $request->file('classimage');
        $class_image = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('classes', $img, 'public');
        // $saveimg = $img->store('Classphoto');
        settype($date1, "string");
        settype($date2, "string");
        settype($date3, "string");
        settype($date4, "string");
        settype($date5, "string");
        settype($date6, "string");
        settype($date7, "string");
        $date = $date1 . $date2 . $date3 . $date4 . $date5 . $date6 . $date7;
        $addclass = $class->addclass($request, $date, $class_image, $studentids);
        return redirect('class');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $srcname = $request->searchstdname;
        $class = new MClass();
        $classdetail = $class->get_classdetail($id);
        $instructors = $class->get_instructors();
        $categories = $class->get_category();
        $students = ($request->searchstdname) ? $class->get_student($srcname) : $class->get_student("");
        $studentsid = $class->get_studentid($id);
        $date = $class->get_classdate($id);
        $day = $date->c_day;
        $day1 = substr($day, 0, 1);
        $day2 = substr($day, 1, 1);
        $day3 = substr($day, 2, 1);
        $day4 = substr($day, 3, 1);
        $day5 = substr($day, 4, 1);
        $day6 = substr($day, 5, 1);
        $day7 = substr($day, 6, 1);
        return inertia("EditClass", [
            'classdata' => $classdetail,
            'instructors' => $instructors,
            'category' => $categories,
            'student' => $students,
            "day" => $day,
            "date1" => $day1,
            "date2" => $day2,
            "date3" => $day3,
            "date4" => $day4,
            "date5" => $day5,
            "date6" => $day6,
            "date7" => $day7,
            "studentsids" => $studentsid
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // AdminValidation
    public function update(AdminValidation $request, $id)
    {
        // if($request->file("classimage"));
        $cdyas = "";

        $cdyas = (int)$request->day1 . (int)$request->day2 . (int)$request->day3 . (int)$request->day4 . (int)$request->day5 . (int)$request->day6 . (int)$request->day7;


        $class = new MClass();
        $class->c_name = $request->classnames;
        $class->c_description = $request->classdetail;
        $class->c_start_date = $request->startdate;
        $class->c_end_date = $request->enddate;
        $class->c_day = $cdyas;

        $dbStudent1 = TStudentClass::where('class_id', $id)->selectRaw('user_id')->get();
        $incomeStudent = $request->students;

        $deleteStudent = [];

        for ($g = 0; $g < count($dbStudent1); $g++) {
            array_push($deleteStudent, $dbStudent1[$g]->user_id);
        }

        // echo '<pre>';

        if($incomeStudent != null){
            $deleteStudent = array_diff($deleteStudent, $incomeStudent);
        }

        for ($i = 0; $i < count($deleteStudent); $i++) {
            TStudentClass::where("user_id", '!=', $deleteStudent[array_keys($deleteStudent)[$i]])->where('class_id', $id)->delete();
        }

        if($request->students != null){
            for ($i = 0; $i < count($request->students); $i++) {
                $perStudent = TStudentClass::where("user_id", $request->students[$i])->where('class_id', $id)->first();
                if ($perStudent == null) {
                    // dd('hi');
                    $student = new TStudentClass();
                    $student->user_id = $request->students[$i];
                    $student->class_id = $id;
                    $student->start_join = date("Y-m-d");
                    $student->save();
                }
            }
        }

        $class_image = $request->file("classimage");
        $studentids = $request->input("students");

        if($class_image != null){
            $class = new MClass();
            $classinfo = $class->searchById($id);
            $oldphoto = explode('/', $classinfo->c_profile);
            Storage::disk('digitalocean')->delete('blogs/' . $oldphoto[4]);

            $file = $request->file("classimage");
            $class_image = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('classes', $file, 'public');
        }

        $editclass = $class->editclass($request, $cdyas, $class_image, $studentids, $id);

        return redirect("class");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
