<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecentActionController extends Controller
{
    public function getRecentAction()
    {
        $recentActions = DB::table('audits')
            ->leftJoin('m_admins', 'm_admins.id','=','audits.m_admin_id')
            ->selectRaw('audits.event,audits.auditable_type,audits.url,audits.updated_at,m_admins.name')
            ->orderBy('audits.updated_at','desc')
            ->paginate(10);

        return inertia('RecentAction',['recentActions'=>$recentActions]);
    }
}
