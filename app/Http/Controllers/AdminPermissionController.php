<?php

namespace App\Http\Controllers;

use App\Models\MPage;
use App\Models\MRole;
use App\Models\MRolePage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Mockery\Expectation;

class AdminPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $routeList = Route::getRoutes();

        // dd($routeList);

        $pages = MPage::where('p_route','!=','adminPermission')->get();

        $role_pages = MRole::where('id','!=',session()->get('roleId'))->get();

        foreach ($role_pages as  $role_page) {
            $role_page->page;
        }

        return inertia('AdminPermission', [
            'role_page' => $role_pages->map(fn($rolePages) => [
                'roleId' => $rolePages->id,
                'roleName' => $rolePages->r_name,
                'page' => $rolePages->page
            ]),
            'permissionPages' => $pages->map(fn($page) => [
                'pageId' => $page->id,
                'pageName' => $page->p_name
            ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Redirect::route('adminPermission.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rolePage = new MRolePage();
        
        try {
            MRolePage::where('m_role_id','!=',session()->get('roleId'))->delete();

        for ($i = 0; $i < count($request->lists); $i++) {
            $rolePage = new MRolePage();
            $rolePage->m_page_id = $request->lists[$i]["pageId"];
            $rolePage->m_role_id = $request->lists[$i]["roleId"];
            $rolePage->created_by = session()->get('adminId');
            $rolePage->save();
        }

        return Redirect::route('adminPermission.index');        
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'occurerror' => 'An error occured'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::route('adminPermission.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Redirect::route('adminPermission.index');
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
        return Redirect::route('adminPermission.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Redirect::route('adminPermission.index');
    }
}
