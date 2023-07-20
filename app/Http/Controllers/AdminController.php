<?php

namespace App\Http\Controllers;

use App\Mail\CreateAdmin;
use App\Mail\EditAdmin;
use App\Models\MAdmin;
use App\Models\MClass;
use App\Models\MInstructor;
use App\Models\MRole;
use App\Models\MSitemasterMyschool;
use App\Models\MSitemasterPublic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;




function randomGenerate()
{
    $char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    $randString = "";

    for ($i = 0; $i < 125; $i++) {
        $index =  rand(0, strlen($char) - 1);
        $randString .= $char[$index];
    }
    return $randString;
}




class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $sec = session()->get('adminId');
        $getadmin = new MAdmin();
        $admin = $getadmin->showAdmin();
        $adminRole = MRole::get();

        return inertia('Admin', [
            'admins' => $admin->through(fn ($admins) => [
                'adminId' => encrypt($admins->id),
                'same' => ($sec == $admins->id) ? true : false,
                'email' => $admins->email,
                'name' => $admins->name,
                'role' => $admins->r_name,
                'roleId' => $admins->roleid,
                'delete' => $admins->del_flg
            ]),
            'sessionId' => $sec,
            'mRole' => $adminRole
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $characterLength = MAdmin::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_admins')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();


        $roles = MRole::where("del_flg", 0)
            ->get();

        return  inertia('AddAdmin', ['roles' => $roles, 'charlength' => $characterLength]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        // dd($_SERVER);
        $request->validate(
            [
                'name' => 'unique:m_admins,name|required',
                'email' => 'unique:m_admins,email|email|required',
                'password' => 'required|min:8',
                'role' => 'exists:m_roles,id'
            ]
        );
        
        $rstr = randomGenerate();
        $siteModel = new MSitemasterMyschool();
        $siteData = $siteModel->idUpdateOrgData();
        // dd($siteData);
        $logo = $siteData[0]->logo;
        $sitename = $siteData[0]->sitename;
        $facebook_link = $siteData[0]->facebook_link;
        $youtube_link1 = $siteData[0]->youtube_link1;
        $youtube_link2 = $siteData[0]->youtube_link2;
        $pModel = new MSitemasterPublic();
        $phoneData = $pModel->uplaod_public();
        $phone = $phoneData[0]->phones;
        // dd($phoneData,$siteData);
        $mail = [
            'email' => $request->email,
            'password' => $request->password,
            'RandomNunber' => $rstr,
            'route' => $_SERVER['HTTP_ORIGIN'] . "/activate/" . $rstr,
            'logo' => $logo,
            'sitename' => $sitename,
            'facebook_link' => $facebook_link,
            'youtube_link1' => $youtube_link1,
            'youtube_link2' => $youtube_link2,
            'phone' => $phone
        ];


        // dd($mail);
        $roles = MRole::where("del_flg", 0)
            ->get();


        try {
            Mail::to($request->email)->send(new CreateAdmin($mail));
            // 
            if ($request->role == 3) {

                $Aadmin = new MAdmin();
                $Aadmin->name = $request->name;
                $Aadmin->email = $request->email;
                $Aadmin->password = Hash::make($request->password);
                $Aadmin->role_id = $request->role;
                $Aadmin->secret_code = $rstr;
                $Aadmin->save();
                $instructor = new MInstructor();
                $instructor->i_name = $request->name;
                $Aadmin->M_Instructor()->save($instructor);
            } else {

                $Aadmin = new MAdmin();
                $Aadmin->name = $request->name;
                $Aadmin->email = $request->email;
                $Aadmin->password = Hash::make($request->password);
                $Aadmin->role_id = $request->role;

                $Aadmin->secret_code = $rstr;
                $Aadmin->save();
            }

            // $addadmin->Addadmin($request);
            return Redirect::route('admin.index');
        } catch (Exception $e) {

            return  redirect()->back()->with('message', 'Mail Could Not Sent Successfully');
        }
        // $suc = Mail::to($request->email)->send(new CreateAdmin($mail));
        // if ($suc == null) {
        //     return Redirect::route('admin.index');
        // } else {
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::route('admin.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $adminId = decrypt($id);

        $characterLength = MAdmin::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_admins')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();
        $sec = session()->get('roleId');
        // dd(session()->get('adminId'),$id);
        $edadmin = new MAdmin();
        $adminInfo = $edadmin->searchAdmin($adminId);

        if ($adminInfo == null) {
            return Redirect::route('admin.index');
        } else {
            $roles = MRole::where("del_flg", 0)
                ->get();
            // dd($adminInfo);
            return inertia('EditAdmin', ['adminInfo' => $adminInfo, 'roles' => $roles, 'sessionId' => $sec, 'charlength' => $characterLength]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dbemail = MAdmin::where('id',$id)->get();
        $request->validate(
            [
                'name' => 'required',
                'email' =>  $request->email == $dbemail[0]->email ? 'email|required' : 'email|required|unique:m_admins,email',
                'password' => 'required|min:8',
                'role' => 'exists:m_roles,id'
            ]
        );
        // dd("ok");
        $siteModel = new MSitemasterMyschool();
        $siteData = $siteModel->idUpdateOrgData();
        // dd($siteData);
        $logo = $siteData[0]->logo;
        $sitename = $siteData[0]->sitename;
        $facebook_link = $siteData[0]->facebook_link;
        $youtube_link1 = $siteData[0]->youtube_link1;
        $youtube_link2 = $siteData[0]->youtube_link2;
        $pModel = new MSitemasterPublic();
        $phoneData = $pModel->uplaod_public();
        $phone = $phoneData[0]->phones;
        $mail = [
            'email' => $request->email,
            'password' => $request->password,
            'logo' => $logo,
            'sitename' => $sitename,
            'facebook_link' => $facebook_link,
            'youtube_link1' => $youtube_link1,
            'youtube_link2' => $youtube_link2,
            'phone' => $phone,
            'login_route' => $_SERVER['HTTP_ORIGIN'] . "/login",
        ];

        $upadmin = MAdmin::find($id);
        $admin = new MAdmin();
        $findadmin = $admin->searchAdmin($id);
        $inst = MInstructor::where("admin_id", $id)->get();
        if(count($inst)!=0){
            $class = MClass::where("instructor_id", $inst[0]->id)->get();
            if(count($class)!=0){
                return redirect()->back()->with('errorC', 'Class is taken');
            }
        }
            if ($request->email == $findadmin->email) {
                
                    if ($request->roles == 3) {
                        $upadmin->name = $request->name;
                        $upadmin->email = $request->email;
                        $upadmin->role_id = $request->roles;
                        if ($request->password != null) $upadmin->password = Hash::make($request->password);
                        $upadmin->save();
                        $instructor = new MInstructor();
                        $instructor->i_name = $request->name;
                        $upadmin->M_Instructor()->save($instructor);
                    } else {
                        $upadmin->name = $request->name;
                        $upadmin->email = $request->email;
                        $upadmin->role_id = $request->roles;
                        if ($request->password != null) $upadmin->password = Hash::make($request->password);
                        $upadmin->save();
                        $instructor = new MInstructor();
                        $instructor->i_name = $request->name;
                        $upadmin->M_Instructor()->delete($instructor);
                    }
                    return Redirect::route('admin.index');
            } else {
                try {
                    Mail::to($request->email)->send(new EditAdmin($mail));
                    if ($request->roles == 3) {
                        $upadmin->name = $request->name;
                        $upadmin->email = $request->email;
                        $upadmin->role_id = $request->roles;
                        if ($request->password != null) $upadmin->password = Hash::make($request->password);
                        $upadmin->save();
                        $instructor = new MInstructor();
                        $instructor->i_name = $request->name;
                        $upadmin->M_Instructor()->save($instructor);
                    } else {
                        $upadmin->name = $request->name;
                        $upadmin->email = $request->email;
                        $upadmin->role_id = $request->roles;
                        if ($request->password != null) $upadmin->password = Hash::make($request->password);
                        $upadmin->save();
                        $instructor = new MInstructor();
                        $instructor->i_name = $request->name;
                        $upadmin->M_Instructor()->delete($instructor);
                    }
                    return Redirect::route('admin.index');
                } catch (Exception $e) {
                    return  redirect()->back()->with('message', 'Mail Could Not Sent Successfully');
                }
            }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deladmin = MAdmin::find($id);
        $deladmin->del_flg == 1 ?  $deladmin->del_flg = 0 : $deladmin->del_flg = 1;
        $deladmin->save();


        return Redirect::route('admin.index');
    }

    public function activate($code)
    {

        $madmin = MAdmin::where('secret_code', $code)
            ->first();

        if ($madmin == null) {
            abort(530);
        }

        $admin = Madmin::find($madmin->id);
        $admin->verify = 1;
        $admin->save();
        abort(520);
        // return Redirect::route('login');
    }
}
