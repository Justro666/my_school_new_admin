<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\MAdmin;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Fortify\Http\Responses\LoginResponse;

class LoginController extends Controller
{
    //
    public function store(Request $request)
    {
            // dd($request);
            $admin = MAdmin::where('email','=',$request->email)->first();
            // dd($admin->verify);
            // $verify = MAdmin::find($request->verify);
        
            // dd($verify);
            $request->validate([
                'email'=>'exists:m_admins,email'
            ]);
            // dd($admin);
            if($admin->verify == 1){
                if ($admin->del_flg == 0) {
                    if(Hash::check($request->password , $admin->password)){
                        session()->put('adminId', $admin->id);
                        session()->put('roleId', $admin->role_id);
        
                        return redirect('/dashboard');
                    }else{
                       return redirect()->back()->with('message','Your password did not match');
                    };
                }else{
                    return redirect()->back()->with('ban','Your Account is Suspended');
                }

                
            }else{
                return redirect()->back()->with('verify','Your Account is Not Verify Yet!');
            }
            
        // $request->authenticate();
            
        // $request->session()->regenerate();

       
    }
}
