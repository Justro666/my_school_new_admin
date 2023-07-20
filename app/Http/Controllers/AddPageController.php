<?php

namespace App\Http\Controllers;

use App\Models\MPage;
use App\Models\MRolePage;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AddPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = MPage::orderBy('id')->paginate(10);

        foreach ($pages as  $page) {
            $page->role;
        }

        // dd($pages);

        return inertia('PageList', [
            'permissionPages' => $pages->through(fn ($page) => [
                'pageId' => Crypt::encrypt($page->id),
                'pageName' => $page->p_name,
                'pageRoute' => $page->p_route,
                'role' => $page->role,
                'publish' => $page->del_flg,
                'menu' => $page->menu,
                'pageIcon' => $page->p_icon
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
        $characterLength = DB::table('m_pages')
            ->select("CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("COLUMN_NAME", "=", "p_name")
            ->orWhere("COLUMN_NAME", "=", "p_route")
            ->get();
        // dd($characterLength);

        return inertia('AddPage', [
            'characterLength' => $characterLength
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $page = new MPage();
        if ($request->menu == 1) {
            $request->validate([
                'page_name' => 'required',
                'page_route' => 'required',
                'pageIcon' => 'required'
            ]);

            // DB::transaction(function () use ($request, $page) {
            $checkData = DB::table('m_pages')
                ->where('p_name', '=', $request->page_name)
                ->orWhere('p_route', '=', $request->page_route)
                ->get();

            if (count($checkData) == 0) {
                try {
                    $file = $request->file("pageIcon");
                    $page_image = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('pageIcons', $file, 'public');
                    $page->insertData($request, $page_image);
                    return Redirect::route('pageList.index');
                } catch (Exception $e) {
                    return redirect()->back()->withErrors([
                        'occurerror' => 'An error occured'
                    ]);
                }
            } else {
                return;
            }
            // });
        } else {
            $request->validate([
                'page_name' => 'required',
                'page_route' => 'required'
            ]);

            // DB::transaction(function () use ($request, $page) {
            $checkData = DB::table('m_pages')
                ->where('p_name', '=', $request->page_name)
                ->orWhere('p_route', '=', $request->page_route)
                ->get();

            if (count($checkData) == 0) {
                try {
                    $page->p_name = $request->page_name;
                    $page->p_route = strtolower($request->page_route);
                    $page->created_by = session()->get('adminId');
                    $page->save();

                    $pages = MPage::where('p_route', $page->p_route)->first();

                    $rolePage = new MRolePage();
                    $rolePage->m_page_id = $pages->id;
                    $rolePage->m_role_id = session()->get('roleId');
                    $rolePage->created_by = session()->get('adminId');
                    $rolePage->save();

                    return Redirect::route('pageList.index');
                } catch (Exception $e) {
                    return redirect()->back()->withErrors([
                        'occurerror' => 'An error occured'
                    ]);
                }
            } else if (count($checkData) != 0) {
                return;
            }
            // });
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
        return Redirect::route('pageList.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $pageId = decrypt($id);
            $characterLength = DB::table('m_pages')
                ->select("CHARACTER_MAXIMUM_LENGTH")
                ->from("information_schema.COLUMNS")
                ->where("COLUMN_NAME", "=", "p_name")
                ->orWhere("COLUMN_NAME", "=", "p_route")
                ->get();
            // dd($characterLength);

            $pageInfo = DB::table('m_pages')
                ->select('id', 'p_name as name', 'p_route as route', 'p_icon as icon', 'menu', 'del_flg as publish')
                ->find($pageId);

            if ($pageInfo == null) {
                return Redirect::route('pageList.index');
            } else {
                foreach ($pageInfo as $property => $value) {
                    $pgInfo[$property] =  ($property == "id") ?  Crypt::encrypt($value) : $value;
                }

                return inertia('EditPage', [
                    'page' => $pgInfo,
                    'characterLength' => $characterLength
                ]);
            }
        } catch (Exception $e) {
            return Redirect::route('pageList.index');
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
        // dd($request);
        try{
            $id = Crypt::decrypt($id);
        }catch(DecryptException $e){
            abort(404);
        }

        $checkData = MPage::where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where('p_name', '=', $request->page_name)
                    ->orWhere('p_route', '=', $request->page_route);
            })
            ->get();

        if ($request->menu != 0) {
            if ($request->page_icon == null) {
                $request->validate([
                    'page_name' => 'required',
                    'page_route' => 'required',
                ]);

                $checkData = MPage::where('id', '!=', $id)
                    ->where(function ($query) use ($request) {
                        $query->where('p_name', '=', $request->page_name)
                            ->orWhere('p_route', '=', $request->page_route);
                    })
                    ->get();

                if (count($checkData) == 0) {
                    try {
                        $page = MPage::find($id);
                        $page->p_name = $request->page_name;
                        $page->p_route = $request->page_route;
                        $page->menu = $request->menu;
                        $page->updated_by = session()->get('adminId');
                        $page->save();

                        return Redirect::route('pageList.index');
                    } catch (Exception $e) {
                        return redirect()->back()->withErrors([
                            'occurerror' => 'An error occured'
                        ]);
                    }
                } else {
                    return;
                }
            } else {
                $page = MPage::find($id);

                if ($page->p_icon != null) {
                    $request->validate([
                        'page_name' => 'required',
                        'page_route' => 'required'
                    ]);

                    $checkData = MPage::where('id', '!=', $id)
                        ->where(function ($query) use ($request) {
                            $query->where('p_name', '=', $request->page_name)
                                ->orWhere('p_route', '=', $request->page_route);
                        })
                        ->get();

                    if (count($checkData) == 0) {
                        try {
                            $oldphoto = explode('/', $page->p_icon);
                            Storage::disk('digitalocean')->delete('pageIcons/' . $oldphoto[4]);

                            $file = $request->file("page_icon");
                            $page_icon = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('pageIcons', $file, 'public');

                            $pages = new MPage();
                            $pages->updateData($request, $page_icon, $id);

                            return Redirect::route('pageList.index');
                        } catch (Exception $e) {
                            return redirect()->back()->withErrors([
                                'occurerror' => 'An error occured'
                            ]);
                        }
                    } else {
                        return;
                    }
                } else {
                    $request->validate([
                        'page_name' => 'required',
                        'page_route' => 'required',
                        'page_icon' => 'required'
                    ]);

                    $checkData = MPage::where('id', '!=', $id)
                        ->where(function ($query) use ($request) {
                            $query->where('p_name', '=', $request->page_name)
                                ->orWhere('p_route', '=', $request->page_route);
                        })
                        ->get();

                    if (count($checkData) == 0) {
                        try {
                            $file = $request->file("page_icon");
                            $page_icon = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('pageIcons', $file, 'public');

                            $pages = new MPage();
                            $pages->updateData($request, $page_icon, $id);

                            return Redirect::route('pageList.index');
                        } catch (Exception $e) {
                            return redirect()->back()->withErrors([
                                'occurerror' => 'An error occured'
                            ]);
                        }
                    } else {
                        return;
                    }
                }
            }
        } else {
            $request->validate([
                'page_name' => 'required',
                'page_route' => 'required'
            ]);

            $checkData = MPage::where('id', '!=', $id)
                ->where(function ($query) use ($request) {
                    $query->where('p_name', '=', $request->page_name)
                        ->orWhere('p_route', '=', $request->page_route);
                })
                ->get();

            if (count($checkData) == 0) {
                $page = MPage::find($id);

                if ($page->p_icon != null) {
                    try {
                        $oldphoto = explode('/', $page->p_icon);
                        Storage::disk('digitalocean')->delete('pageIcons/' . $oldphoto[4]);

                        $pages = new MPage();
                        $page->p_name = $request->page_name;
                        $page->p_route = $request->page_route;
                        $page->p_icon = null;
                        $page->menu = 0;
                        $page->updated_by = session()->get('adminId');
                        $page->save();

                        return Redirect::route('pageList.index');
                    } catch (Exception $e) {
                        return redirect()->back()->withErrors([
                            'occurerror' => 'An error occured'
                        ]);
                    }
                } else {
                    try {
                        $pages = new MPage();
                        $page->p_name = $request->page_name;
                        $page->p_route = $request->page_route;
                        $page->menu = 0;
                        $page->updated_by = session()->get('adminId');
                        $page->save();

                        return Redirect::route('pageList.index');
                    } catch (Exception $e) {
                        return redirect()->back()->withErrors([
                            'occurerror' => 'An error occured'
                        ]);
                    }
                }
            } else {
                return;
            }

            return Redirect::route('pageList.index');
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
        try {
            $id = Crypt::decrypt($id);

            $page = MPage::find($id);
            $page->del_flg == 0 ? $page->del_flg = 1 : $page->del_flg = 0;
            $page->updated_by = session()->get('adminId');
            $page->save();

            return Redirect::route('pageList.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'occurerror' => 'An error occured'
            ]);
        }
    }
}
