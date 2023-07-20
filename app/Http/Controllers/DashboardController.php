<?php

namespace App\Http\Controllers;

use App\Models\MCategory;
use App\Models\MClass;
use App\Models\Mdashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getdashboard()
    {
        $recentActions = DB::table('audits')
            ->leftJoin('m_admins', 'm_admins.id', '=', 'audits.m_admin_id')
            ->selectRaw('audits.event,audits.auditable_type,audits.url,audits.updated_at,m_admins.name')
            ->limit(3)
            ->orderBy('audits.updated_at', 'desc')
            ->get();

        $students = DB::table('m_categories')
            ->LeftJoin('m_classes', 'm_classes.category_id', '=', 'm_categories.id')
            ->leftJoin('t_student_classes', 't_student_classes.class_id', '=', 'm_classes.id')
            ->groupBy('m_categories.c_name')
            ->selectRaw('m_categories.c_name, count(m_classes.id) AS studentsCount')
            ->get();

        $classdata = DB::table('m_classes')
            ->join('m_instructors', 'm_classes.instructor_id', '=', 'm_instructors.id')
            ->leftjoin('t_student_classes', 'm_classes.id', '=', 't_student_classes.class_id')
            ->selectRaw("COUNT('t_student_classes.class_id') AS StudenyCount,m_classes.id, m_classes.c_name, m_classes.c_day, m_classes.c_start_time, m_classes.c_end_time, m_classes.c_fees, m_instructors.i_name,m_classes.created_at")
            ->orderBy('m_classes.created_at', 'desc')
            ->where('m_classes.del_flg', 0)
            ->groupBy("m_classes.id")
            ->get();

        $regionDatas = [];
        for ($i = 1; $i <= 15; $i++) {
            $regions = DB::table("users")
                ->selectRaw("count(region) as region")
                ->groupBy("region")
                ->where("region", $i)
                ->first();
            array_push($regionDatas, ($regions == null) ? 0 : $regions->region);
        }

        //    dd($regionData);

        return inertia("Dashboard", [
            'students' => $students,
            'classdata' => $classdata,
            'recentActions' => $recentActions,
            'regionDatas' => $regionDatas
        ]);
    }
}
