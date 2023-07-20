<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatevalidateRequest;
use App\Models\MAdmin;
use App\Models\MClass;
use App\Models\MVideo;
use App\Models\TLectureNote;
use App\Rules\VideoDateValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;

class AddVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Redirect::route('class.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return Redirect::route('class.index');
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
        // $date = $request->date;
      
        $model2 = new MClass();
        $classData = $model2->getClasses($request->classId);

        $startdate = strtotime($classData[0]->c_start_date);
        $enddate = strtotime($classData[0]->c_end_date);
        // dd($startdate);

        // dd($classDate);
        $request->validate([
            'videoName' => "required",
            'description' => 'required',
            'date' => [
                'required', new VideoDateValidation($startdate, $enddate), 'date_format:Y-m-d'
            ],
            'storage' => 'required',
            'storagelocation' => 'required',

        ]);

        DB::transaction(function () use ($request) {
            $sessionId = session()->get('adminId');
            $videoUpload = new MVideo();
            $videoUpload->v_name = $request->videoName;
            $videoUpload->v_description = $request->description;
            $videoUpload->v_date = $request->date;
            $videoUpload->v_storage_link = $request->storage;
            $videoUpload->v_storage_location = $request->storagelocation;
            $videoUpload->class_id = $request->classId;
            $videoUpload->created_by = $sessionId;
            $videoUpload->save();


            $lectureUpload = [];
            for ($i = 0; $i < count($request->lecturename); $i++) {
                $sessionId = session()->get('adminId');
                $ledb = new TLectureNote();
                $ledb->l_name = $request->lecturename[$i];
                $ledb->created_by = $sessionId;
                if ($request->alecturefile[$i] != null && $request->astoragelink[$i] == null) {
                    echo "<pre>";
                    var_dump($request->alecturefile[$i]);
                    $file = $request->alecturefile[$i][0];
                    // dd($file);
                    // $fileupload = $file->storePublicly("Leacture", ['disk' => 'public']);
                    $fileupload = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('lectureNotes', $file, 'public');
                    $ledb->l_storage_link = $fileupload;

                    // dd($fileupload);
                } else if ($request->astoragelink[$i] != null && $request->alecturefile[$i] == null) {
                    $ledb->l_storage_link = $request->astoragelink[$i];
                    $ledb->l_storage_location = $request->lecturelocation[$i];
                    // dd( $request->astoragelink[$i]);
                }
                array_push($lectureUpload, $ledb);
            }
            $videoUpload->TLectureNote()->saveMany($lectureUpload);
        });
        return Redirect::route("class.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = new MClass();
        $classData = $model->getClasses($id)->where('del_flg', 0);


        $characterLength = MVideo::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_videos')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();
        // dd($classData[0]->id);

        $lectureLength = TLectureNote::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 't_lecture_notes')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();
        if (count($classData) == 0) {
            return Redirect::route('class.index');
        } else {
            return inertia('AddVideo', ['classDdata' => $classData, 'charLength' => $characterLength, 'lectureLength' => $lectureLength]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = new MClass();


        $video = MVideo::find($id);
        $classData = $model->getClasses($video->class_id)->where('del_flg', 0);

        $characterLength = MVideo::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 'm_videos')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();
        // dd($classData[0]->id);

        $lectureLength = TLectureNote::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
            ->where("TABLE_NAME", "=", 't_lecture_notes')
            ->where('CHARACTER_MAXIMUM_LENGTH', '!=', NULL)
            ->get();
        // dd($characterLength,$lectureLength);
        if ($video == null) {
            return Redirect::route('class.index');
        } else {
            $class = MClass::find($video->class_id);

            $video->TLectureNote;
            // dd($characterLength);
            // dd($video);
            return inertia('EditVideo', [
                "videoData" => $video,
                'classDdata' => $class,
                'charLength' => $characterLength,
                'classId' => $classData,
                'lectureLength' => $lectureLength
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
        // dd($request);
        $model = new MClass();
        $video = MVideo::find($id);
        $classData = $model->getClasses($video->class_id)->where('del_flg', 0);
        $startdate = strtotime($classData[0]->c_start_date);
        $enddate = strtotime($classData[0]->c_end_date);
        // dd($classData);
        $request->validate([
            'videoName' => "required",
            'description' => 'required',
            'date' => ['required', new VideoDateValidation($startdate, $enddate), 'date_format:Y-m-d'],
            'storage' => 'required',
            'storagelocation' => 'required',

        ]);
        
        DB::transaction(function () use ($request, $id) {
            $sessionId = session()->get('adminId');
            $videoUpload = MVideo::find($id);
            $videoUpload->v_name = $request->videoName;
            $videoUpload->v_description = $request->description;
            $videoUpload->v_date = $request->date;
            $videoUpload->v_storage_link = $request->storage;
            $videoUpload->v_storage_location = $request->storagelocation;
            $videoUpload->updated_by =$sessionId;
            // $videoUpload->class_id = $request->classId;
            $videoUpload->save();

            $leDatas = TLectureNote::where('video_id', $id)->get();
            $leDatasid = [];
            $reqLectid = [];
            $leDatas2 = [];
            $fileName = [];
            $userLecture = $request->alecturefile;

            for ($oops = 0; $oops < count($leDatas); $oops++) {
                array_push($leDatasid, $leDatas[$oops]->id);
            }
            for ($opps = 0; $opps < count($request->alectureid); $opps++) {
                if ($request->alectureid[$opps] != null) {
                    array_push($reqLectid, $request->alectureid[$opps]);
                }
            }
            $manualDeleteLect = array_diff($leDatasid, $reqLectid);

            for ($m = 0; $m < count($manualDeleteLect); $m++) {
                $findLecture = TLectureNote::where('id', $manualDeleteLect[array_keys($manualDeleteLect)[$m]])->get();
                $expData = explode('/', $findLecture[0]->l_storage_link);
                if (count($expData) >= 5) {
                    array_push($leDatas2, $expData[2]);
                    array_push($fileName, $expData[4]);
                }
            }

            for ($qq = 0; $qq < count($userLecture); $qq++) {
                if ($request->alecturefile[$qq] != null) {
                    if ($request->alectureid[$qq] != null) {
                        $findLecture = TLectureNote::where('id', $request->alectureid[$qq])->get();
                        $expData = explode('/', $findLecture[0]->l_storage_link);
                        if (count($expData) >= 5) {
                            array_push($leDatas2, $expData[2]);
                            array_push($fileName, $expData[4]);
                        }
                    }
                } elseif ($request->alecturefile[$qq] == null) {
                    if ($request->alectureid[$qq] != null) {
                        $findLecture = TLectureNote::where('id', $request->alectureid[$qq])->get();
                        if ($request->astoragelink[$qq] != $findLecture[0]->l_storage_link) {
                            // dd('same inside');
                            $expData = explode('/', $findLecture[0]->l_storage_link);
                            if (count($expData) >= 5) {
                                array_push($leDatas2, $expData[2]);
                                array_push($fileName, $expData[4]);
                            }
                        }
                    }
                }
            }
            for ($e = 0; $e < count($leDatas2); $e++) {
                if ($leDatas2[$e] == "myschoolstorage.sgp1.digitaloceanspaces.com") {
                    // dd($fileName[$e]);
                    Storage::disk('digitalocean')->delete('lectureNotes/' . $fileName[$e]);
                }
            }
            TLectureNote::where("video_id", $id)->delete();
            
            $lectureUpload = [];
            for ($i = 0; $i < count($request->lecturename); $i++) {
                $sessionId = session()->get('adminId');
                $ledb = new TLectureNote();
                $ledb->l_name = $request->lecturename[$i];
                $ledb->updated_by =$sessionId;

                // dd($request);
                // if ($request->alecturefile[$i] != null) {
                //     dd($request->astoragelink[$i]);
                // }
                if ($request->alecturefile[$i] != null) {
                    // echo "<pre>";
                    // var_dump($request->alecturefile[$i]);
                    $file = $request->alecturefile[$i][0];
                    // $fileupload = $file->storePublicly("Leacture", ['disk' => 'public']);
                    $fileupload = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('lectureNotes', $file, 'public');
                    $ledb->l_storage_link = $fileupload;

                    // dd($fileupload);
                } else if ($request->astoragelink[$i] != null && $request->alecturefile[$i] == null) {
                    $ledb->l_storage_link = $request->astoragelink[$i];
                    $ledb->l_storage_location = $request->lecturelocation[$i];
                    // dd( $request->astoragelink[$i]);
                }
                array_push($lectureUpload, $ledb);
            }
            $videoUpload->TLectureNote()->saveMany($lectureUpload);
        });
        return Redirect::route("class.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $videoUpload = MVideo::find($id);
        $videoUpload->del_flg = 1;
        $videoUpload->save();
        TLectureNote::where("video_id", $id)
            ->update(['del_flg' => '1']);
        return Redirect::route("class.index");
    }
}
