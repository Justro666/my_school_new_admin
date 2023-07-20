<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Contracts\Auditable;

class MStudent extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function getStudents()
    {
        return DB::table("users")->where('del_flg', 0)->get();
    }



    public function allStuents($selectedItem = [], $search = "")
    {
        // $query = DB::select("SELECT COUNT(t_student_classes.class_id),users.age,users.email,users.phone,users.address,users.name  FROM `users`
        //         JOIN t_student_classes
        //         ON t_student_classes.user_id = users.id
        //         GROUP BY t_student_classes.user_id");
        $query = DB::table("users")
            ->leftjoin("t_student_classes", "t_student_classes.user_id", "=", "users.id")
            ->leftjoin("m_classes", "m_classes.id", "=", "t_student_classes.class_id");


        // dd($selectedItem);
        $query->when(empty($selectedItem) && empty($search), function ($query) {
            // dd("1");
            return
                $query->groupBy("t_student_classes.user_id")->selectRaw("COUNT('t_student_classes.class_id') AS Class,users.age,users.email,users.phone,users.address,users.name,users.id")
                ->where('t_student_classes.class_id', '!=', null);
        });

        $query->when($search, function ($query) use ($search) {
            // dd("2");
            return  $query
                ->groupBy("t_student_classes.user_id")
                ->selectRaw("COUNT('t_student_classes.class_id') AS Class,users.age,users.email,users.phone,users.address,users.name,users.id")
                ->where('name', 'like', '%' . $search . '%')
                ->where('t_student_classes.class_id', '!=', null)
                ->orWhere('address', 'like', '%' . $search . '%');
        });

        $query->when($selectedItem && $selectedItem[0] == 0, function ($query) use ($selectedItem) {
            // dd("3");
            return $query
                ->selectRaw("users.age,users.email,users.phone,users.address,users.name,users.id")
                ->where('t_student_classes.class_id', null);
        });

        $query->when($selectedItem && $selectedItem[0] != 0, function ($query) use ($selectedItem) {
            // dd("4");
            return $query
                ->groupBy("t_student_classes.user_id")
                ->selectRaw("COUNT('t_student_classes.class_id') AS Class,users.age,users.email,users.phone,users.address,users.name,users.id")
                // ->select("*")
                ->whereIn('m_classes.category_id', $selectedItem);
        });
        // dd($search);

        $messages = $query->paginate(10)->withQueryString();
        // 
        // dd($messages);


        // echo "<pre>";
        // print_r($query);
        // dd($query);
        return $messages;
    }

    public function studnetDetailandClasses($id)
    {
        $query = DB::table("users")
            ->join("t_student_classes", "t_student_classes.user_id", "=", "users.id")
            ->join("m_classes", "m_classes.id", "=", "t_student_classes.class_id")

            ->selectRaw("GROUP_CONCAT(m_classes.c_name) AS Class,users.name,users.age,users.phone,users.address,users.email,users.profile_photo_path")
            ->where("users.id", "=", $id)
            // ->select("*")
            ->get();

        return $query;
    }
    // $query = DB::table("t_student_attendances")
    //     ->join("m_classes", "m_classes.id", "=", "t_student_attendances.class_id")
    //     ->join("users", "users.id", "=", "t_student_attendances.user_id")
    //     // ->join("t_student_exams", "t_student_exams.user_id", "=", "users.id")
    //     ->selectRaw("AVG(t_student_attendances.attendance) AS attendance,users.name,users.id,t_student_attendances.class_id")
    //     // ->select("*")
    //     ->where('t_student_attendances.user_id', $id)
    //     ->groupBy("t_student_attendances.class_id")
    //     ->get();
    // dd($query);
    // echo "<pre>";
    // print_r($query);
    // return $query;
    // public function studentExam($id){
    //     $query = DB::table("users")
    //         -// ->leftJoin("t_student_exams", "t_student_exams.user_id", "=", "users.id")
    //         ->select("name","")

    // }


    public function studentAccount($request, $password, $id)
    {
        return DB::table("users")
            ->insert([
                'name' => $request->name,
                'email' => $request->email,
                'created_by' => $id,
                'password' => Hash::make($password)
            ]);
    }

    public function category()
    {
        $query = DB::table("m_categories")
            ->select('*')
            ->get();

        return $query;
    }
}
