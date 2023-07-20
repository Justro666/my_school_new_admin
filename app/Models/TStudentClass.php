<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TStudentClass extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function totalClass($id)
    {
        $query = DB::table('t_student_classes')
            ->join('m_classes', 't_student_classes.class_id', '=', 'm_classes.id')
            ->where('t_student_classes.user_id', $id)
            ->orderBy('t_student_classes.class_id')
            ->get();



        return $query;
        // dd($query);
    }

    public function totalStudents($id){

      
        $query = DB::select("SELECT count(t_student_classes.id) as counts FROM `t_student_classes` WHERE t_student_classes.class_id IN ($id) GROUP BY class_id;");
        
        return $query;
    }
}

// const props = defineProps({
//     studenProfile: Object,
//     classes: Object, //full data of class
//     attendance: Object, //attendance,class name,class id
//     examRanks: Object, //
//     rank_mark: Object,
//     all_ranks: Object,
//     one_class: Object,
//     exam_percent: Object,
//     overall_rank: Object,
//     class_rank: Object,
// });
