<?php

namespace App\Http\Controllers;

use App\Models\M_Instructor;
use App\Models\MInstructor;
use App\Models\MStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InstructorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $model1 = new MStudent();
        $category = $model1->category();

        $model = new  M_Instructor();
        if ($request->searchname) {
            $instructor = $model->instructorsForInspage($request->searchname);
        } else {
            $instructor = $model->instructorsForInspage();
        }
        // dd($instructor);


        // echo "<pre>";
        // print_r($instructor);
        // dd("ok");
        return Inertia::render('Instructor', [
            'instructors' => $instructor = array_map(fn ($inst) => [
                'instId' => encrypt($inst->id),
                'name' => $inst->i_name,
                'class' => $inst->CLASSES,
                'address' => $inst->i_address,
                'contact' => $inst->i_contact,
                'student' => $inst->STUDENT

            ], $instructor), 'categorys' => $category
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $admin
        // return inertia('AddInstructor');
        return Redirect::route('instructor.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Redirect::route('instructor.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::route('instructor.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $characterLength = MInstructor::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_instructors')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();

        // dd($characterLength);
        $model = new M_Instructor();
        $insData = $model->insEdit(decrypt($id));
        // dd($insData);
        return Inertia::render('EditInstructor', ['insData' => $insData, 'charLength' => $characterLength]);
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
        $sessionId = session()->get('adminId');
        $insUpdate = M_Instructor::find($id);
        $insUpdate->i_address = $request->address;
        $insUpdate->i_contact = $request->contact;
        $insUpdate->updated_by = $sessionId;

        $insUpdate->save();
        return Redirect::route('instructor.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Redirect::route('instructor.index');
    }
}
