<?php

namespace App\Http\Controllers;

use App\Models\MCategory;
use App\Models\MPrivacyPolicy;
use Exception;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $privacypolicys = DB::table('m_privacy_policies')
            ->join('m_categories', 'm_categories.id', '=', 'm_privacy_policies.category_id')
            ->selectRaw('m_privacy_policies.id,m_privacy_policies.p_title,m_privacy_policies.p_description,m_privacy_policies.del_flg,m_privacy_policies.updated_at,m_categories.c_name')
            ->orderBy('m_privacy_policies.updated_at', 'desc')
            ->paginate(10);

        // dd($privacypolicys);

        return inertia('PrivacyPolicyTool', [
            'privacypolicys' => $privacypolicys->through(fn ($privacypolicy) => [
                'privacyId' => Crypt::encrypt($privacypolicy->id),
                'title' => $privacypolicy->p_title,
                'description' => $privacypolicy->p_description,
                'publish' => $privacypolicy->del_flg,
                'date' => $privacypolicy->updated_at,
                'category' => $privacypolicy->c_name
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
        $categories = MCategory::where("del_flg", 0)->get();

        $characterLength = DB::table('m_privacy_policies')
            ->select("CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("COLUMN_NAME", "=", "p_title")
            ->orWhere("COLUMN_NAME", "=", "p_description")
            ->get();

        return inertia('AddPrivacyPolicy', [
            'categories' => $categories,
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
        $request->validate([
            'privacypolicys_title' => 'required',
            'privacypolicys_description' => 'required',
            'category' => 'required'
        ]);

        try {
            $privacypolicys = new MPrivacyPolicy();
            $privacypolicys->insertData($request);

            return Redirect::route('privacypolicyTool.index');
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
        return Redirect::route('privacypolicyTool.index');
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
            $privacyId = Crypt::decrypt($id);
            $privacypolicys = new MPrivacyPolicy();
            $privacypolicysInfo = $privacypolicys->searchById($privacyId);

            $characterLength = DB::table('m_privacy_policies')
                ->select("CHARACTER_MAXIMUM_LENGTH")
                ->from("information_schema.COLUMNS")
                ->where("COLUMN_NAME", "=", "p_title")
                ->orWhere("COLUMN_NAME", "=", "p_description")
                ->get();

            if ($privacypolicysInfo == null) {
                return Redirect::route('privacypolicyTool.index');
            } else {
                $categories = MCategory::all();

                foreach ($privacypolicysInfo as $property => $value) {
                    $privacyInfo[$property] =  ($property == "id") ?  Crypt::encrypt($value) : $value;
                }

                return inertia('EditPrivacyPolicy', [
                    'privacypolicysInfo' => $privacyInfo,
                    'categories' => $categories->map(fn ($category) => [
                        'categoryId' => $category->id,
                        'categoryName' => $category->c_name
                    ]),
                    'characterLength' => $characterLength
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'occurerror' => 'An error occured'
            ]);
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
        $request->validate([
            'privacypolicys_title' => 'required',
            'privacypolicys_description' => 'required',
            'category' => 'required'
        ]);

        try {
            $id = Crypt::decrypt($id);
            $privacypolicys = new MPrivacyPolicy();
            $privacypolicys->updateData($request, $id);

            return Redirect::route('privacypolicyTool.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'occurerror' => 'An error occured'
            ]);
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
            $privacypolicys = new MPrivacyPolicy();
            $privacypolicys->deleteData($id);

            return Redirect::route('privacypolicyTool.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'occurerror' => 'An error occured'
            ]);
        }
    }
}
