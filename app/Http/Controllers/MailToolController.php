<?php

namespace App\Http\Controllers;

use App\Mail\AlertMail;
use App\Mail\DirectMessageMail;
use App\Mail\InformationMail;
use App\Models\MClass;
use App\Models\MStudent;
use App\Models\TMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Monolog\Handler\PushoverHandler;
use App\Events\realtimeNoti;
use App\Models\MSitemasterMyschool;
use App\Models\MSitemasterPublic;
use Exception;
use Illuminate\Support\Facades\Crypt;

class MailToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getMails = new TMail();
        $mails = $getMails->getMails();

        // dd($mails);

        return inertia('MailTool', [
            'mails' => $mails->through(fn($mail) => [
                'mailId' => Crypt::encrypt($mail->id),
                'title' => $mail->m_title,
                'description' => $mail->m_description,
                'category' => $mail->m_category,
                'username' => $mail->name,
                'classname' => $mail->c_name,
                'date' => $mail->created_at
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
        $characterLength = DB::table('t_mails')
            ->select("CHARACTER_MAXIMUM_LENGTH")
            ->from("information_schema.COLUMNS")
            ->where("COLUMN_NAME", "=", "m_title")
            ->orWhere("COLUMN_NAME", "=", "m_description")
            ->get();

        // dd($characterLength);

        $getStudents = new MStudent();
        $students = $getStudents->getStudents();

        $classes = MClass::where('del_flg', 0)->get();

        return inertia('AddMail', [
            'students' => $students->map(fn ($student) => [
                'studentId' => Crypt::encrypt($student->id),
                'studentName' => $student->name
            ]),
            'classes' => $classes->map(fn ($class) => [
                'classId' => Crypt::encrypt($class->id),
                'className' => $class->c_name
            ]),
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
        $dataForMails = MSitemasterMyschool::all();
        $dataForMailsAdditional = MSitemasterPublic::all();

        $logo = $dataForMails[0]->logo;
        $siteName = $dataForMails[0]->sitename;
        $facebook = $dataForMails[0]->facebook_link;
        $youtube1 = $dataForMails[0]->youtube_link1;
        $youtube2 = $dataForMails[0]->youtube_link2;
        $youtube3 = $dataForMails[0]->youtube_link3;
        $address = $dataForMails[0]->address;
        $email = $dataForMails[0]->email;
        $phone = $dataForMailsAdditional[0]->phones;
        $copyright = $dataForMailsAdditional[0]->copyright;

        $mail = new TMail();

        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);


        if ($request->choice == false) {
            try {
                $mailArray = [];

                $classId = decrypt($request->class);
                $class = MClass::find($classId);

                $data = [
                    'logo' => $logo,
                    'sitename' => $siteName,
                    'facebook' => $facebook,
                    'youtube1' => $youtube1,
                    'youtube2' => $youtube2,
                    'youtube3' => $youtube3,
                    'address' => $address,
                    'email' => $email,
                    'phone' => $phone,
                    'copyright' => $copyright,
                    'name' => $class->c_name,
                    'title' => $request->title,
                    'description' => $request->description
                ];

                $allMails = $mail->getEmails($classId);
                for ($i = 0; $i < count($allMails); $i++) {
                    array_push($mailArray, $allMails[$i]->email);
                }

                if ($request->category == 1) {
                    Mail::to($allMails)->send(new InformationMail($data));
                } else if ($request->category == 2) {
                    Mail::to($allMails)->send(new DirectMessageMail($data));
                } else if ($request->category == 3) {
                    Mail::to($allMails)->send(new AlertMail($data));
                }

                $mail->insertClassMail($request,$classId);

                return Redirect::route('mailtool.index');
            } catch (Exception $e) {
                return redirect()->back()->withErrors([
                    'occurerror' => 'An error occured'
                ]);
            }
        } else if ($request->choice == true) {
            try {
                $userId = decrypt($request->student);
                $user = User::find($userId);
                $userMail = $user->email;
                $data = [
                    'logo' => $logo,
                    'sitename' => $siteName,
                    'facebook' => $facebook,
                    'youtube1' => $youtube1,
                    'youtube2' => $youtube2,
                    'youtube3' => $youtube3,
                    'address' => $address,
                    'email' => $email,
                    'phone' => $phone,
                    'copyright' => $copyright,
                    'name' => $user->name,
                    'title' => $request->title,
                    'description' => $request->description
                ];

                if ($request->category == 1) {
                    Mail::to($userMail)->send(new InformationMail($data));
                } else if ($request->category == 2) {
                    Mail::to($userMail)->send(new DirectMessageMail($data));
                } else if ($request->category == 3) {
                    Mail::to($userMail)->send(new AlertMail($data));
                }
                $mail->insertStudentMail($request, $userId);

                return Redirect::route('mailtool.index');
            } catch (Exception $e) {
                return redirect()->back()->withErrors([
                    'occurerror' => 'An error occured'
                ]);
            }

            // event(new realtimeNoti(["noti", $user->id]));

        }

        return Redirect::route('mailtool.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::route('mailtool.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Redirect::route('mailtool.index');
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
        return Redirect::route('mailtool.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Redirect::route('mailtool.index');
    }
}
