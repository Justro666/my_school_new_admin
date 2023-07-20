<?php

namespace App\Http\Controllers;

use App\Models\MClass;
use App\Models\TStudentAttendance;
use App\Models\TStudentClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * 
     */
    public function index()
    {
        $class = MClass::all();
        return inertia("Attendance/Attendance", ["classes" => $class]);
    }

    /**
     * 
     */
    public function getStudents(Request $request)
    {
        $class = MClass::all();
        $students = User::join('t_student_classes', 'users.id', '=', 't_student_classes.user_id')
            ->where("t_student_classes.del_flg", 0)
            ->where("t_student_classes.class_id", $request->id)
            ->get();
        $attendance = TStudentAttendance::where("class_id", $request->id)
            ->where("date", $request->date)->get();
        $attendanceSummary = TStudentAttendance::selectRaw("user_id,users.name AS name, AVG(attendance) * 100 As percentage")
            ->join("users", "users.id", "=", "t_student_attendances.user_id")
            ->where("class_id", $request->id)->groupBy("user_id")
            ->orderby("percentage","desc")
            ->get();

        return inertia("Attendance/Attendance", [
            "classes" => $class,
            "students" => $students,
            "attendance" => $attendance,
            "summary" => $attendanceSummary
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'class' => 'required'
        ]);

        if ($request->students)
            TStudentAttendance::where("class_id", $request->class)
                ->where("date", $request->date)->delete();
        for ($attloop = 0; $attloop < count($request->students); $attloop++) {
            $attendance = new TStudentAttendance();
            $attendance->user_id = $request->students[$attloop]["user_id"];
            $attendance->class_id = $request->class;
            $attendance->attendance = (float)$request->attendance[$attloop];
            $attendance->date = $request->date;
            $attendance->save();
        }

        $class = MClass::all();
        $attendanceSummary = TStudentAttendance::selectRaw("user_id,users.name AS name, AVG(attendance) * 100 As percentage")
        ->join("users", "users.id", "=", "t_student_attendances.user_id")
        ->where("class_id", $request->class)->groupBy("user_id")->get();

        return inertia("Attendance/Attendance", [
            "classes" => $class,
            "summary" => $attendanceSummary
        ]);
    }
}
