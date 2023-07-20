<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TMail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function insertClassMail($request,$classId){
        $tmail = new TMail();
        $tmail->m_title = $request->title;
        $tmail->m_description = $request->description;
        $tmail->class_id = $classId;
        $tmail->m_category = $request->category;
        $tmail->created_by = session()->get('adminId');
        $tmail->save();
    }

    public function insertStudentMail($request,$userId){
        $tmail = new TMail();
        $tmail->m_title = $request->title;
        $tmail->m_description = $request->description;
        $tmail->user_id = $userId;
        $tmail->m_category = $request->category;
        $tmail->created_by = session()->get('adminId');
        $tmail->save();
    }

    public function getEmails($classId){
        $query = DB::table('users')
            ->select('email')
            ->join('t_student_classes','users.id','=','t_student_classes.user_id')
            ->where('t_student_classes.class_id','=',$classId)
            ->get();
        return $query;

        // SELECT `email` FROM `users` AS user JOIN `t_student_classes` AS class ON user.id = class.user_id WHERE class_id = 1
    }  

    public function getMails(){
        return DB::table('t_mails')
            ->leftJoin('users','users.id','=','t_mails.user_id')
            ->leftJoin('m_classes','m_classes.id','=','t_mails.class_id')
            ->selectRaw('t_mails.id,t_mails.m_title,t_mails.m_description,t_mails.m_category,users.name,m_classes.c_name,t_mails.created_at')
            ->where('t_mails.del_flg',0)
            ->orderBy('t_mails.updated_at', 'desc')
            ->paginate(10);
    } 
}
