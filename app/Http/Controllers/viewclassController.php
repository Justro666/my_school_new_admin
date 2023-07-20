<?php

namespace App\Http\Controllers;

use App\Models\MClass;
use App\Models\MVideo;
use Illuminate\Http\Request;

class viewclassController extends Controller
{
    public function getclassdata($id)
    {

        $class = new MClass();
        $video = new MVideo();
        $videodata = $video->get_videos($id);

        // dd($videodata);


        $classdata = $class->get_classdata($id);

        $studentlistData = $class->forStudentList($id);
        // dd($id);
        // dd($studentlistData);
        return inertia("ViewClass", [
            'classdata' => $classdata,
            'studentList' => $studentlistData,
            'videos' => $videodata
            //     ->through( fn ($videodatas)=>[
            //     'videoId'=>$videodatas->videoId,
            //     'name'=>$videodatas->v_name,
            //     'date'=>$videodatas->v_date,
            //     'lecture'=>$videodatas->Lecture
            // ])
        ]);
    }
}
