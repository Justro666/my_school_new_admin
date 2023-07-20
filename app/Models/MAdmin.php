<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Contracts\Auditable;

class MAdmin extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function M_Instructor()
    {
        return $this->hasOne(M_Instructor::class, "admin_id");
    }

    public function updateAdminRole($ad_id, $roleId)
    {
        DB::table('m_admin')->where('id', $ad_id)->update([
            'role' => $roleId

        ]);
    }

    // public function Addadmin($request)
    // {
    //     // DB::table('m_admin')
    //     // ->insert([
    //     //     'name'=>$request->name,
    //     //     'email'=>$request->email,
    //     //     'password'=>$request->password,
    //     //     'role'=>$request->role

    //     // ]);

    //         // dd($request);
        
    // }

    public function searchAdmin($id)
    {
        // $ID = decrypt($id);
        $sAdmin = DB::table('m_admins')
            ->where('id', $id)
            ->first();
        return $sAdmin;
    }

    // public function updateAdmin($request, $id)
    // {

         //     // dd($request);

    //     $upadmin = MAdmin::find($id);
    //         $upadmin->name = $request->name;
    //         $upadmin->email = $request->email;
    //         $upadmin->role_id = $request->roles;
    //         if ($request->password != null) $upadmin->password = Hash::make($request->password);
    //         $upadmin->save();
    //     // if ($request->roles == 3) {
            
    //     // } else {
    //     //     $upadmin = MAdmin::find($id);
    //     //     $instructor = DB::table('m_instructors')
    //     //         ->select('i_name')
    //     //         ->get();
    //     //         // dd($instructor);
    //     //         for ($i=0; $i < count($instructor) ; $i++) { 
    //     //             if ($request->name == $instructor[$i]->i_name ) {
                            
    //     //             };
    //     //         }
    //     //     dd($instructor);
            
    //     // }
    // }
    public function showAdmin()
    {
        $admin = MAdmin::join('m_roles', 'm_roles.id', '=', 'm_admins.role_id')
            ->selectRaw("m_admins.id,m_admins.name,m_admins.email,m_roles.r_name,m_admins.del_flg,m_roles.id AS roleid")
            ->orderBy("m_admins.id")
            ->paginate(10);
        return $admin;
    }

    public function checkAdminEmail($email)
    {
        $hasemail = MAdmin::where('email', '=', $email)->get();

        return $hasemail;
    }

    // public function checkAdmin($request){
    //     dd($request);
    //     $admin = MAdmin::find($request->email , $request->password);
    //     $admin->save();


    // }
    // public function deleteAdmin($id){

    // }
}
