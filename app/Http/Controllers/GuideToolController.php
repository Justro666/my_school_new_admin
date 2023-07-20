<?php

namespace App\Http\Controllers;

use App\Models\MGuide;
use App\Models\MGuideStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;

class GuideToolController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $guides = MGuide::paginate(10);
        foreach ($guides as $guide) {
            $guide->guideStep;
        }
        // dd($guides);
        return inertia('GuideTool', ['guides' => $guides
        ->through( fn ($guidesInfo)=>[
            'guideId'=>encrypt($guidesInfo->id),
            'title'=>$guidesInfo->g_title,
            'step'=>$guidesInfo->guideStep,
            'create'=>$guidesInfo->created_at,
            'delete'=>$guidesInfo->del_flg
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
        $characterLength = MGuide::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
        ->from("information_schema.COLUMNS")
        ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
        ->where("TABLE_NAME", "=", 'm_guides')
        ->where('CHARACTER_MAXIMUM_LENGTH','!=',NULL)
        ->get();

        $stepLength = MGuideStep::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
        ->from("information_schema.COLUMNS")
        ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
        ->where("TABLE_NAME", "=", 'm_guide_steps')
        ->where('CHARACTER_MAXIMUM_LENGTH','!=',NULL)
        ->get();
       
        return inertia('Addguide',['charLength'=>$characterLength,'stepLength'=>$stepLength]);
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
            'guidetitle' => 'unique:m_guides,g_title|required',
            'steptitle' => 'required',
            'description' => 'required',
            'step_file'=> 'required'
        ]);

        // dd($request);
        DB::transaction(function() use($request){
            // dd($request);
            $sessionId = session()->get('adminId');
            $addguide = new MGuide();
            $addguide->g_title = $request->guidetitle;
            $addguide->created_by =$sessionId;
            
    
            $steps = [];
            for ($step = 0; $step < count($request->steptitle); $step++) {
                $gStep = new MGuideStep();
                $sessionId = session()->get('adminId');
                $file = $request->step_file[$step][0];
                // $guidephoto = $file->storePublicly("Guide", ['disk' => 'public']);
                $guidephoto = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('guides', $file, 'public');
                //    dd($guidephoto);
                $gStep->step =  $step + 1;
                $gStep->step_title =   $request->steptitle[$step];
                $gStep->step_description = $request->description[$step];
                $gStep->step_photo = $guidephoto;
                $gStep->updated_by =$sessionId;
                array_push($steps, $gStep);
            }
            $addguide->save();
            $addguide->guideStep()->saveMany($steps);
        });
       
        return Redirect::route('guideTool.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::route('guideTool.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $guideId = decrypt($id);
        // dd($guideId);
        $guides = MGuide::find($guideId);
        $characterLength = MGuide::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
        ->from("information_schema.COLUMNS")
        ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
        ->where("TABLE_NAME", "=", 'm_guides')
        ->where('CHARACTER_MAXIMUM_LENGTH','!=',NULL)
        ->get();

        $stepLength = MGuideStep::selectRaw("COLUMN_NAME,CHARACTER_MAXIMUM_LENGTH")
        ->from("information_schema.COLUMNS")
        ->where("TABLE_SCHEMA", "=", env('DB_DATABASE'))
        ->where("TABLE_NAME", "=", 'm_guide_steps')
        ->where('CHARACTER_MAXIMUM_LENGTH','!=',NULL)
        ->get();
       

        if ($guides == null) {
            return Redirect::route('guideTool.index');
        } else {
            $guides->guideStep;
            return inertia("EditGuide", ["guideInfo" => $guides,'charLength'=>$characterLength,'stepLength'=>$stepLength]);
        }
        

       
        // return $privacypolicysInfo;
        // return inertia('EditGuide',['guideInfo' => $guidesInfo]);
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
        //    dd($request->stepid);
       
        // for($);
        $dstep = MGuideStep::where("guide_id", $id)->get();
        dd($dstep);
        $dbid =[];
        for($o = 0 ; $o < count($dstep);$o++){
            array_push($dbid, $dstep[$o]->id);
        }
        // dd($dbid);
        $result = array_diff($dbid,$request->stepid);
        // dd($result);
        foreach($result as $a => $val){
            $delphotos = MGuideStep::where("id",$result[$a])->get();
            $old_p = $delphotos[0]->step_photo;
            $del_p = explode("/",$old_p);
            
            // Storage::disk('digitalocean')->delete('guides' . $del_p[4]);
            Storage::disk('digitalocean')->delete('guides/' .$del_p[4]);
            echo "<pre>";
            print_r ($del_p[4]);
        }
        // dd("ok");
        
        $request->validate([
            'guidetitle' => 'required',
            'steptitle' => 'required',
            'description' => 'required',
            'step_file'=> 'required'
        ]);

        DB::transaction(function() use($request,$id) {
        $sessionId = session()->get('adminId');
        $update = MGuide::find($id);
        $update->g_title = $request->guidetitle;
        $update->updated_by =$sessionId;
            
        $steps = [];
        for ($step = 0; $step < count($request->steptitle); $step++) {
            $gStep = new MGuideStep();
            $sessionId = session()->get('adminId');
            if($request->fake_id[$step]!=null) {
            $dbStep = MGuideStep::where( "id",$request->stepid[$step])->get();
            // dd($dbStep);

            if($dbStep[0]->step_photo != $request->step_file[$step]){
                $oldPhoto = $dbStep->step_photo;
                $delfile =  explode('/', $oldPhoto);
                Storage::disk('digitalocean')->delete('guides/  ' . $delfile[3]);
                
            $file = $request->step_file[$step][0];
            // dd($file);
                $guidephoto = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('guides', $file, 'public');
                // dd($guidephoto);
                dd($dbStep);
            $gStep->step_photo = $guidephoto;

            }else{
                
                $gStep->step_photo = $request->step_file[$step];
                
            }
            $gStep->step =  $step + 1;
            $gStep->step_title =   $request->steptitle[$step];
            $gStep->step_description = $request->description[$step];
            $gStep->updated_by =$sessionId;
            array_push($steps, $gStep);
        }
        else{
            $file = $request->step_file[$step][0];
                $guidephoto = env("DO_URL") . "/" . Storage::disk('digitalocean')->put('guides', $file, 'public');
                $gStep->step_photo = $guidephoto;
                $gStep->step =  $step + 1;
                $gStep->step_title =   $request->steptitle[$step];
                $gStep->step_description = $request->description[$step];
                $gStep->updated_by =$sessionId;
                array_push($steps, $gStep);
        }
           
        }
        MGuideStep::where("guide_id", $id)->delete();
        $update->save();
        $update->guideStep()->saveMany($steps);
    });
        return Redirect::route('guideTool.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sessionId = session()->get('adminId');
        $delguide = MGuide::find($id);
        $delguide->updated_by =$sessionId;
        $delguide->del_flg == 0 ? $delguide->del_flg = 1 : $delguide->del_flg = 0;
        $delguide->save();
        MGuideStep::where("guide_id", $id)
                    
            ->update(['del_flg' => '1']);

        // $steps = [];
        // for ($step=0; $step < count( $request->steptitle) ; $step++) { 
        //    $gStep = new MGuideStep();
        //    $gStep->del_flg = 1 ;
        //    array_push($steps, $gStep);
        // }

        return Redirect::route("guideTool.index");
    }
}
