<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TStudentAttendance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function getAttendance($id)
    {
        return DB::select(
            "SELECT DISTINCT t_student_attendances.class_id, AVG(t_student_attendances.attendance) as attend, m_classes.c_name
        FROM t_student_attendances JOIN m_classes ON t_student_attendances.class_id = m_classes.id
        WHERE t_student_attendances.user_id = :id
        GROUP BY(t_student_attendances.class_id)",
            [":id" => $id]
        );
    }
}
