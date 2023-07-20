<?php

namespace App\Http\Middleware;

use App\Models\MPage;
use App\Models\MRole;
use App\Models\MRolePage;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('adminId') && $request->session()->has('roleId')) {
            $path = explode("/",$request->path());

            $page = MPage::where('p_route', strtolower($path[0]))->first();
           
            $rolepage = MRolePage::where('m_page_id', $page->id)
                ->where('m_role_id', session()->get('roleId'))->get();
              
             if(count($rolepage) == 0){
                abort(403);
             };
            return $next($request);
        }
        return redirect('/login');
    }
}
