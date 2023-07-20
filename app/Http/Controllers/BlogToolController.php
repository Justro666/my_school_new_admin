<?php

namespace App\Http\Controllers;

use App\Models\MBlog;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class BlogToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = MBlog::orderBy('updated_at', 'desc')
            ->paginate(10);

        return inertia('BlogTool', [
            'blogs' => $blogs->through(fn ($blog) => [
                'blogId' => Crypt::encrypt($blog->id),
                'title' => $blog->b_title,
                'description' => $blog->b_description,
                'photo' => $blog->b_photo,
                'publish' => $blog->del_flg,
                'date' => $blog->updated_at
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
        $characterLength = DB::table('m_blogs')
            ->select("CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("COLUMN_NAME", "=", "b_title")
            ->orWhere("COLUMN_NAME", "=", "b_description")
            ->get();

        // dd($characterLength);

        return inertia('AddBlog', [
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
        // dd($request->blog_file);
        $request->validate([
            'blog_title' => 'required',
            'blog_description' => 'required',
            'blog_file' => 'required'
        ]);

        try {
            $file = $request->file("blog_file");
            $blog_image = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('blogs', $file, 'public');


            $blogs = new MBlog();
            $blogs->insertData($request, $blog_image);

            return Redirect::route('blogTool.index');
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
        return Redirect::route('blogTool.index');
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
            $blogId = Crypt::decrypt($id);
            $blogs = new MBlog();
            $blogsInfo = $blogs->searchById($blogId);

            $characterLength = DB::table('m_blogs')
                ->select("CHARACTER_MAXIMUM_LENGTH")
                ->from("information_schema.COLUMNS")
                ->where("COLUMN_NAME", "=", "b_title")
                ->orWhere("COLUMN_NAME", "=", "b_description")
                ->get();

            // dd($characterLength);

            if ($blogsInfo == null) {
                return Redirect::route('blogTool.index');
            } else {
                foreach ($blogsInfo as $property => $value) {
                    $blogInfo[$property] =  ($property == "id") ?  Crypt::encrypt($value) : $value;
                }

                return inertia('EditBlog', [
                    'blogsInfo' => $blogInfo,
                    'characterLength' => $characterLength
                ]);
            }
        } catch (Exception $e) {
            return Redirect::route('blogTool.index');
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
        try{
            $id = Crypt::decrypt($id);
        }catch(DecryptException $e){
            abort(404);
        }

        if ($request->blog_file == null) {
            $request->validate([
                'blog_title' => 'required',
                'blog_description' => 'required'
            ]);

            try {
                $blog = MBlog::find($id);
                $blog->b_title = $request->blog_title;
                $blog->b_description = $request->blog_description;
                $blog->updated_by = session()->get('adminId');
                $blog->save();

                return Redirect::route('blogTool.index');
            } catch (Exception $e) {
                return redirect()->back()->withErrors([
                    'occurerror' => 'An error occured'
                ]);
            }
        }

        $request->validate([
            'blog_title' => 'required',
            'blog_description' => 'required',
            'blog_file' => 'required'
        ]);

        try {
            $blogs = new MBlog();
            $bloginfo = $blogs->searchById($id);
            $oldphoto = explode('/', $bloginfo->b_photo);
            Storage::disk('digitalocean')->delete('blogs/' . $oldphoto[4]);

            $file = $request->file("blog_file");
            $blog_image = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('blogs', $file, 'public');
            $blogs->updateData($request, $blog_image, $id);

            return Redirect::route('blogTool.index');
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
            $blogs = new MBlog();
            $blogs->deleteData($id);

            return Redirect::route('blogTool.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'occurerror' => 'An error occured'
            ]);
        }
    }
}
