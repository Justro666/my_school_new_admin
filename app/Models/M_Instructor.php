<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class M_Instructor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function madmin()
    {
        return $this->belongsTo(MAdmins::class);
    }

    public $table = 'm_instructors';
    use HasFactory;

    public function get_Names()
    {
        $names = DB::table('m_admin')
            ->get();

        return $names;
    }

    public function addInstructor($i_role,  $request)
    {
        DB::table('m_instructors')
            ->insert([
                'ad_id' => $request->input('ad_id'),
                'i_name' => $request->input('name'),
                'i_contact' => $request->input('contact'),
                'i_address' => $request->input('address'),

                'role_id' => $i_role,
                'created_at' => Date('Y-m-d h:i:s'),
                'created_by' => "0"
            ]);
    }
    public function showInstructor($id)
    {
        return DB::table('m_instructors')->join('m_admin', 'm_instructors.ad_id', "=", "m_admin.id")->where('ad_id', $id)->get();
    }
    public function updateInstructor($i_id, $request)
    {
        DB::table('m_instructors')->where('ad_id', $i_id)->update([
            'i_name' => $request->input('name'),
            'i_contact' => $request->input('contact'),
            'i_address' => $request->input('address'),
            'created_at' => Date('Y-m-d h:i:s'),
            'created_by' => "0"
        ]);
    }
    public function getAllInstructor()
    {
        return
            DB::table('m_classes')
            ->join('m_instructors', 'm_classes.instructor_id', '=', 'm_instructors.id')
            ->join('m_categories', 'm_classes.category_id', '=', 'm_categories.id')
            ->where('m_classes.del_flg', 0)
            ->where('m_instructors.del_flg', 0)
            ->orderby("m_instructors.created_at", "desc")
            ->get();

        // DB::table('m_instructors')->where('del_flg', 0)->orderby("created_at", "desc")->get();
    }
    public function getCategories()
    {
        return
            DB::table('m_categories')
            ->where('del_flg', 0)
            ->get();

        // DB::table('m_instructors')->where('del_flg', 0)->orderby("created_at", "desc")->get();
    }

    public function instructorsForInspage($search = "")
    {

        if ($search == "") {
            $query = "SELECT
                    ins.id,ins.i_name,ins.i_contact,ins.i_address,COUNT(ts.class_id) as STUDENT, (
                SELECT
                    COUNT(instructor_id)
                FROM
                    m_classes
                WHERE
                    instructor_id = ins.id
                ) AS CLASSES
                FROM
                     m_instructors AS ins
                LEFT JOIN m_classes AS c
                ON
                    c.instructor_id = ins.id
                LEFT JOIN t_student_classes AS ts
                ON
                ts.class_id = c.id
                GROUP by ins.id";
        } else {
            // dd($search);
            $query = "SELECT
                    ins.id,ins.i_name,ins.i_contact,ins.i_address,COUNT(ts.class_id) as STUDENT, (
                SELECT
                    COUNT(instructor_id)
                FROM
                    m_classes
                WHERE
                    instructor_id = ins.id
                ) AS CLASSES
                FROM
                     m_instructors AS ins
                LEFT JOIN m_classes AS c
                ON
                    c.instructor_id = ins.id
                LEFT JOIN t_student_classes AS ts
                ON
                ts.class_id = c.id
                WHERE ins.i_name LIKE  '%$search%'
                GROUP by ins.id";
        }
        $results = DB::select(DB::raw($query));
        // dd($results);
        return $results;
        // $sub = 'SELECT COUNT(instructor_id)FROM m_classes WHERE instructor_id = ins.id AS CLASSES';

        // $query = DB::table('m_instructors')
        //     ->leftJoin('m_classes', 'm_classes.instructor_id', '=', 'm_instructors.id')
        //     ->leftJoin('t_student_classes', 't_student_classes.class_id', '=', 'm_classes.id')
        //     ->selectRaw('m_instructors.id,m_instructors.i_name,m_instructors.i_contact,m_instructors.i_address,COUNT(t_student_classes.class_id) as STUDENT')
        //     ->mergeBindings($sub->getQuery())
        //     ->groupBy('m_instructors.id')
        //     ->get();

        // dd($query);
    }

    public function insEdit($id)
    {
        return DB::table('m_instructors')
            ->leftJoin('m_admins', 'm_admins.id', '=', 'm_instructors.admin_id')
            ->selectRaw("m_instructors.i_name,m_instructors.i_contact,m_instructors.i_address,m_admins.email,m_instructors.id")
            // ->select('*')
            ->where("m_instructors.id", '=', $id)
            ->get();

        // dd($quere
    }
}
