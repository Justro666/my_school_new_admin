<?php

namespace App\Http\Middleware;

use App\Models\MAdmin;
use App\Models\MContact;
use App\Models\MRole;
use App\Models\MRolePage;
use App\Models\MSitemasterMyschool;
use App\Models\MSitemasterPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {

        $admin = MAdmin::find(session()->get('adminId'));
        $role = MRole::find(session()->get('roleId'));
        $notiCount = MContact::where('m_contacts.opened','=', 0)->get();



        // $mrolePage = MRolePage::where('m_role_id', session()->get('roleId'))->get();
        $mainPages = DB::table('m_role_pages')
            ->leftJoin('m_pages', 'm_pages.id', '=', 'm_role_pages.m_page_id')
            ->where('m_role_pages.m_role_id', session()->get('roleId'))
            ->where('m_pages.menu', '=', 1)
            ->where('m_pages.del_flg', '=', 0)
            ->selectRaw('m_pages.p_name,m_pages.p_route,m_pages.p_icon')
            ->orderBy("m_pages.id")
            ->get();
        // dd($mrolePage);

        $subPages = DB::table('m_role_pages')
        ->leftJoin('m_pages', 'm_pages.id', '=', 'm_role_pages.m_page_id')
        ->where('m_role_pages.m_role_id', session()->get('roleId'))
        ->where('m_pages.menu', '=', 2)
        ->where('m_pages.del_flg', '=', 0)
        ->selectRaw('m_pages.p_name,m_pages.p_route,m_pages.p_icon')
        ->orderBy("m_pages.id")
        ->get();

        // dd($mrolePage);
        if ($admin == null || $role == null) {
            $admin = "";
            $role = "";
        } else {
            $admin = $admin->name;
            $role = $role->r_name;
        }


        return array_merge(parent::share($request), [
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'pmessage' => fn () => $request->session()->get('message'),
                'verify' =>fn () => $request->session()->get('verify'),
                'banned' =>fn () => $request->session()->get('ban'),
                'worng' => fn () => $request->session()->get('message'),
                'mailSent' => fn () => $request->session()->get('mailsent'),
                'errorclass'=>fn()=>$request->session()->get('errorC'),

            ],
            'auth' => [
                'username' => ($request->path() == "login") ? "" : $admin,
                'userrole' => ($request->path() == "login") ? "" : $role,
            ],
            'setting' => [
                'logo' => fn () => MSitemasterMyschool::find(1)->logo,
                'favicon'=> fn () => MSitemasterMyschool::find(1)->favicon,
                'sitename' => fn () => MSitemasterMyschool::find(1)->sitename
            ],
            'pages' => [
                'mainPages' => $mainPages,
                'subPages' => $subPages
            ],
            'notiCount' => count($notiCount)
        ]);
    }
}
