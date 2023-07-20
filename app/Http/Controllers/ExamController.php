<?php

namespace App\Http\Controllers;

use App\Models\MClass;
use App\Models\MExam;
use App\Models\TStudentExam;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = MExam::where("m_exams.del_flg", 0)
            ->join("m_classes", "m_classes.id", "=", "m_exams.class_id")
            ->selectRaw("m_exams.*,m_classes.id AS classid,m_classes.c_name AS c_name")
            ->get();
        return inertia("Exam/Exam", ["exams" => $exams]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class = MClass::all();
        return inertia("Exam/AddExam", ["classes" => $class]);
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
            'date' => 'required',
            'class' => 'required',
            'examName' => 'required',
            'fullmark' => 'required|numeric',
            'failmark' => 'required|numeric'
        ]);

        $exam = new MExam();
        $exam->e_name = $request->examName;
        $exam->class_id = $request->class;
        $exam->e_duedate = $request->date;
        $exam->full_mark = $request->fullmark;
        $exam->fail_mark = $request->failmark;

        $exam->save();

        return redirect("/exam");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = MExam::where("del_flg", 0)
            ->where("id", $id)->first();
        if ($exam == null) {
            abort(404);
        }
        $class = MClass::all();
        return inertia("Exam/EditExam", ["classes" => $class, "exam" => $exam]);
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
        $request->validate([
            'date' => 'required',
            'class' => 'required',
            'examName' => 'required',
            'fullmark' => 'required|numeric',
            'failmark' => 'required|numeric'
        ]);

        $exam = MExam::find($id);
        $exam->e_name = $request->examName;
        $exam->class_id = $request->class;
        $exam->e_duedate = $request->date;
        $exam->full_mark = $request->fullmark;
        $exam->fail_mark = $request->failmark;

        $exam->save();

        return redirect("/exam");
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

    /**
     * 
     */
    public function entryMark($id)
    {
        $classid = MExam::find($id);
        if ($classid == null) {
            abort(403);
        }

        //get class name
        $className = MClass::find($classid->class_id)->c_name;

        //get exam list
        $exams = MExam::where("class_id", $classid->class_id)
            ->where("del_flg", 0)->get();

        //get selected Exam
        $selectedExam = MExam::find($id);

        //get student list
        $students = User::join('t_student_classes', 'users.id', '=', 't_student_classes.user_id')
            ->where("t_student_classes.del_flg", 0)
            ->where("t_student_classes.class_id", $classid->class_id)
            ->get();

        $student_exams = User::join('t_student_exams', 'users.id', '=', 't_student_exams.user_id')
            ->where("t_student_exams.exam_id", $id)
            ->get();

        $examSummary = TStudentExam::selectRaw("user_id,users.name AS name, 
        ((SUM(t_student_exams.mark) * 100)/SUM(full_mark)) AS percentage")
            ->join("users", "users.id", "=", "t_student_exams.user_id")
            ->join("m_exams", "m_exams.id", "=", "t_student_exams.exam_id")
            ->where("m_exams.class_id", $classid->class_id)->groupBy("user_id")
            ->orderby("percentage", "desc")
            ->get();

        return inertia(
            "Exam/EntryMark",
            [
                "exams" => $exams,
                "className" => $className,
                "selectedExam" => $selectedExam,
                "students" => $students,
                "summary" => $examSummary,
                "student_exams" => $student_exams
            ]
        );
    }

    /**
     * 
     */
    public function saveMark(Request $request)
    {


        $request->validate([
            'marks' => 'required'
        ]);

        // delete previous data
        TStudentExam::where("exam_id", $request->exam)->delete();

        DB::transaction(function () use ($request) {
            for ($studentExam = 0; $studentExam < count($request->students); $studentExam++) {
                // add current data
                $t_student_exam = new TStudentExam();
                $t_student_exam->user_id = $request->students[$studentExam]["user_id"];
                $t_student_exam->exam_id = $request->exam;

                try {
                    $t_student_exam->mark = $request->marks[$studentExam];
                } catch (Exception $e) {
                    return redirect()->back()->withErrors([
                        'marks' => 'Mark field is required.'
                    ]);
                }

                $t_student_exam->created_by = session()->get("adminId");
                $t_student_exam->save();
            }
        });

        return redirect("/exam/entrymark/" . $request->exam);
    }
}
