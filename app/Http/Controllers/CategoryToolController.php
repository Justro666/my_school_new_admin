<?php

namespace App\Http\Controllers;

use App\Models\MCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CategoryToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = MCategory::where("del_flg", 0)
            ->paginate(10);

        return inertia('CategoryTool', [
            'categories' => $categories->through(fn ($category) => [
                'categoryId' => Crypt::encrypt($category->id),
                'name' => $category->c_name,
                'description' => $category->c_description,
                'date' => $category->updated_at
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
        $characterLength = DB::table('m_categories')
            ->select("CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("COLUMN_NAME", "=", "c_name")
            ->orWhere("COLUMN_NAME", "=", "c_description")
            ->get();

        // dd($characterLength);

        return inertia('AddCategory', [
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
            'category_name' => 'required',
            'category_description' => 'required'
        ]);

        try {
            $categories = new MCategory();
            $categories->c_name = $request->category_name;
            $categories->c_description = $request->category_description;
            $categories->created_by = session()->get('adminId');
            $categories->save();

            return Redirect::route('categoryTool.index');
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
        return Redirect::route('categoryTool.index');
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
            $categoryId = Crypt::decrypt($id);
            $categories = new MCategory();
            $categoriesInfo = $categories->searchById($categoryId);

            $characterLength = DB::table('m_categories')
                ->select("CHARACTER_MAXIMUM_LENGTH")
                ->from("information_schema.COLUMNS")
                ->where("COLUMN_NAME", "=", "c_name")
                ->orWhere("COLUMN_NAME", "=", "c_description")
                ->get();

            // dd($characterLength);

            if ($categoriesInfo == null) {
                return Redirect::route('categoryTool.index');
            } else {
                foreach ($categoriesInfo as $property => $value) {
                    $categoryInfo[$property] =  ($property == "id") ?  Crypt::encrypt($value) : $value;
                }

                return inertia('EditCategory', [
                    'categoriesInfo' => $categoryInfo,
                    'characterLength' => $characterLength
                ]);
            }
        } catch (Exception $e) {
            return Redirect::route('categoryTool.index');
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
            'category_name' => 'required',
            'category_description' => 'required'
        ]);

        try {
            $id = Crypt::decrypt($id);

            $categories = new MCategory();
            $categories->updateData($request, $id);

            return Redirect::route('categoryTool.index');
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
        return Redirect::route('categoryTool.index');
    }
}
