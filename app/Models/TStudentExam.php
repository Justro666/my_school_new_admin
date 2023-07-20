<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class TStudentExam extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function getExamRankPercent($id)
    {
        return DB::select("SELECT (SUM(t_student_exams.mark)/SUM(m_exams.full_mark)) *100 AS exam,m_classes.id FROM `t_student_exams`
        JOIN `m_exams` ON t_student_exams.exam_id = `m_exams`.id
        JOIN `m_classes` ON `m_exams`.`class_id` =  `m_classes`.id
        WHERE t_student_exams.user_id = $id
        GROUP BY `m_classes`.`id`
        ORDER by `m_classes`.`id`");
    }

    public function getExamList()
    {
        return TStudentExam::distinct()->orderby("id")->pluck('exam_id');
    }


    public function showRankTable($exam_id)
    {
        return  DB::select(
            "SELECT
                users.id,
                users.name,
                m_exams.created_at AS  date_submitted,
                m_exams.id as examid,
                m_exams.e_name,
                t_student_exams.mark,
                Rank() OVER(ORDER BY  t_student_exams.mark DESC) `rank`
            FROM
                `t_student_exams`
                JOIN users ON `t_student_exams`.`user_id` = users.id
                JOIN m_exams ON `t_student_exams`.`exam_id` = m_exams.id
                WHERE t_student_exams.exam_id = :id",
            [":id" => $exam_id]
        );
    }

    public function getExamlistByClassID($id)
    {

        return DB::select(
            "SELECT
                `users`.`id` as uid,
                `m_exams`.`created_at` AS  date_submitted,
                `m_exams`.`id` as examid,
                `m_exams`.`e_name`,
                `m_classes`.`id` as cid,
                `t_student_exams`.`mark`,

                 RANK() OVER(PARTITION BY m_exams.id ORDER BY t_student_exams.mark DESC) `rank`

                   FROM `t_student_exams`
          JOIN `m_exams` ON `t_student_exams`.`exam_id` = `m_exams`.`id`
          RIGHT JOIN `users` ON `t_student_exams`.`user_id` = `users`.id
          JOIN `m_classes` ON `m_exams`.`class_id` = `m_classes`.`id`
        --   WHERE `users`.`id`= $id
         WHERE `m_classes`.`id`= $id
        --   GROUP BY `examid`
          ORDER BY `t_student_exams`.`mark` DESC"
        );
    }

    public function getUserRankById($classid)
    {
        return DB::select(
            "SELECT
            users.id,
            users.name,
            SUM(t_student_exams.mark) sumMark,
       		m_exams.e_name,
            m_classes.c_name,

            Rank() OVER(ORDER BY   SUM(t_student_exams.mark) DESC ) ranks
        FROM
                `t_student_exams`
                JOIN users ON `t_student_exams`.`user_id` = users.id
                JOIN m_exams ON `t_student_exams`.`exam_id` = m_exams.id
                JOIN m_classes ON `m_exams`.`class_id` = m_classes.id

                WHERE m_classes.id = :id
                GROUP By t_student_exams.user_id,m_classes.id
                ORDER By m_classes.id",
            [":id" => $classid]
        );
    }

    public function getUserRank()
    {
        return DB::select(
            "SELECT
            users.id,
            users.name,
            SUM(t_student_exams.mark) sumMark,
            Rank() OVER(ORDER BY   SUM(t_student_exams.mark) DESC ) `rank`
        FROM
                `t_student_exams`
                JOIN users ON `t_student_exams`.`user_id` = users.id
                JOIN m_exams ON `t_student_exams`.`exam_id` = m_exams.id
                GROUP By t_student_exams.user_id
                ORDER By `rank`"
        );
    }
}
