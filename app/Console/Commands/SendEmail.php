<?php

namespace App\Console\Commands;

use App\Mail\ReportCard;
use App\Models\TStudentAttendance;
use App\Models\TStudentExam;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDate = date("Y-m-01");
        $endDate = date("Y-m-t");
	$examStartDate = date("Y-m-01 00:00:00");
	$examEndDate = date("Y-m-t 23:59:59");
        $month = date("F");
        $finaldata = [];
       $userId = TStudentAttendance::selectRaw("user_id")
                    ->join("m_classes","m_classes.id","=" , "t_student_attendances.class_id")
                    ->join("users","users.id","=","t_student_attendances.user_id")
                    ->whereBetween("t_student_attendances.date",["$startDate","$endDate"])
                    ->groupby("t_student_attendances.user_id")
                    // ->orderby("user_id","DESC")
                    ->get();

        // echo "<pre>";
//         dd($userId);
        // print($countUserId);

        for ($i=0; $i < count($userId); $i++) {
            $attendance = TStudentAttendance::selectRaw(
                "users.id, users.name, users.email,
                t_student_attendances.class_id,
                    m_classes.c_name,
                    FLOOR(AVG(t_student_attendances.attendance)*100) as attend")
                ->join("m_classes","m_classes.id","=" , "t_student_attendances.class_id")
                ->join("users","users.id","=","t_student_attendances.user_id")
                ->whereBetween("t_student_attendances.date",["$startDate","$endDate"])
                ->where("t_student_attendances.user_id","=",$userId[$i]->user_id)
                ->groupby("t_student_attendances.user_id","m_classes.id")
                ->get();


            $exammarks = DB::select(
                    "SELECT
                    t_student_exams.user_id,
                    FLOOR(SUM(t_student_exams.mark)/SUM(m_exams.full_mark)*100) as exam_mark,
                    m_exams.class_id,
                    SUM(m_exams.full_mark),
                    SUM(t_student_exams.mark),
                    RANK() OVER(PARTITION BY m_exams.class_id ORDER BY SUM(t_student_exams.mark) DESC) `rank`
                FROM
                    `t_student_exams`
                    JOIN m_exams ON `t_student_exams`.`exam_id` = m_exams.id

                    WHERE t_student_exams.created_at BETWEEN '$examStartDate' AND '$examEndDate'

                    GROUP BY `t_student_exams`.`user_id`, `m_exams`.`class_id`
                    ORDER BY `t_student_exams`.`user_id`
                "
            );
//	dd($exammarks);
            for ($x=0; $x < count($attendance); $x++) {
                for ($y=0; $y < count($exammarks) ; $y++) {
                    if($attendance[$x]->id == $exammarks[$y]->user_id && $attendance[$x]->class_id == $exammarks[$y]->class_id){
                        $attendance[$x]->exammark = $exammarks[$y]->exam_mark;
                        $attendance[$x]->rank = $exammarks[$y]->rank;
                    }

                }
            }




            array_push($finaldata,$attendance);

            $maintable= "";
            $show = "block";
            $signdisplay = "none";
            $attendcolor = "";
            $examcolor = "";
            $badgebackground = "";
            $text = "";

            for($y=0; $y < count($finaldata[0]); $y++){
                if($y == count($finaldata[0])-1){
                    $signdisplay = "block";
                }
                if($y>0){
                    $show = "none";
                }
    // Attendence Condition
                if($finaldata[0][$y]->attend == 100){
                    $attendcolor = "#26C223";
                }
                if($finaldata[0][$y]->attend <= 99 && $finaldata[0][$y]->attend >= 75){
                    $attendcolor = "#FFC652";
                }
                if($finaldata[0][$y]->attend < 75){
                    $attendcolor = "#EF4444";
                }
    // Exam Mark Condition
                if($finaldata[0][$y]->exammark == 100){
                    $examcolor = "#26C223";
                    $badgebackground = "url('https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo%2Frank-image%2Frankshield-green.png')";
                }
                if($finaldata[0][$y]->exammark <= 99 && $finaldata[0][$y]->exammark >= 75){
                    $examcolor = "#FFC652";
                    $badgebackground = "url('https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo%2Frank-image%2Frankshield-yellow.png')";
                }
                if($finaldata[0][$y]->exammark < 75){
                    $examcolor = "#EF4444";
                    $badgebackground = "url('https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo%2Frank-image%2Frankshield-red.png')";
                }
    // Paragraph Condition
                if($finaldata[0][$y]->exammark <= 100 && $finaldata[0][$y]->exammark >= 95){
                    $text = "ဒီအတိုင်းဆက်ပြီး ကြိုးစားသွားပါ";
                }
                if($finaldata[0][$y]->exammark <= 94 && $finaldata[0][$y]->exammark >= 80){
                    $text = "%နည်းနည်းလျော့လာတယ်လို့ ထင်ပါတယ်....လုပ်နိုင်တယ်ထင်တာကြောင့် ပိုအားထုတ်စေချင်ပါတယ်";
                }
                if($finaldata[0][$y]->exammark <= 79 && $finaldata[0][$y]->exammark >= 75){
                    $text = "အခုလက်ရှိက အားစိုက်မှုနဲနဲ နဲတာကြောင့် ဒီထက်ပိုအားစိုက်ပေးပါ";
                }
                if($finaldata[0][$y]->exammark <= 74 && $finaldata[0][$y]->exammark >= 50){
                    $text = "75% မပြည့်တာကြောင့် ပြည့်မှီအောင်ကြိုးစားပေးပါ။";
                }
                if($finaldata[0][$y]->exammark <= 49 && $finaldata[0][$y]->exammark >= 0){
                    $text = "သင်တန်းအပေါ်မှာအလေးအနက်ထားပြီးလုပ်ပေးပါ။တောင်းဆိုပါတယ်။";
                }

                $maintable .=
                '
                <table
                border="0"
                cellpadding="0"
                cellspacing="0"
                width="100%"
              >
                <tr>
                  <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px">
                    <table
                      border="0"
                      cellpadding="0"
                      cellspacing="0"
                      width="100%"
                      id="maintable"
                      style="max-width: 600px; background-color: #243438;"
                    >
                      <tr style="display : '.$show.';">
                        <td class="mainheader">
                          <table style="text-align: center; width: 75%;">
                            <tr>
                              <td>
                                <p
                                    style="
                                      margin: 25px 0 0 0;
                                      font-size: 28px;
                                      letter-spacing: 2px;
                                      color: #bdc2c3;
                                      font-weight: 400;
                                      padding-left: 65px;
                                    "
                                    class="header"
                                  >
                                    '.$month.' Report Card
                                  </p>
                              </td>
                            </tr>

                            <tr>
                              <td>
                                <p
                                    style="
                                      margin: 0;
                                      font-size: 15px;
                                      color: #bdc2c3;
                                      font-weight: 200;
                                      letter-spacing: 1px;
                                      padding-left: 65px;
                                    "
                                    class="sub-header"
                                  >
                                    Ex;brain Education
                                  </p>
                              </td>
                            </tr>
                          </table>
                          <table align="right" style="margin: 20px 0 0 20px;">
                            <tr>
                              <td>
                                <img
                                  src="https://myschoolstorage.sgp1.digitaloceanspaces.com/settings/public/darkmode/hzG9HpqSayo4TbWRxCuloHaiTWMwtMfykGSaKUHp.png"
                                  class="logo"
                                  alt=""
                                  width="55px"
                                />
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
            <tr>
                <td>
                    <table style="text-align: center; width: 100%">
                    <tr>
                        <td>
                        <p
                            style="
                            display : '.$show.';
                            color: #54a9f6;
                            font-size: 40px;
                            font-weight: bold;
                            letter-spacing: 2px;
                            "
                            class="data-name"
                        >'.
                            $finaldata[0][$y]->name
                        .'</p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                        <p
                            style="
                            color: #ffffff;
                            font-size: 16px;
                            font-weight: 400;
                            margin-bottom: 30px;
                            "
                        >'.
                        $finaldata[0][$y]->c_name
                        .'</p>
                        </td>
                    </tr>

                  <tr >
                    <td style="width:100%;">
                        <table style="margin: auto;" class="rank">
                            <tr>
                                <td style="">
                                    <span class="" id="badge" style="
                                        font-weight: bold;
                                        font-size: 25px;
                                        color: #fff;
                                        background-image: '.$badgebackground.';
                                        padding: 25px;
                                        background-repeat: no-repeat;
                                        background-size: contain;
                                        background-position: center;
                                    ">'.$finaldata[0][$y]->rank.'</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>

                    <tr >
                        <td  >
                            <table style=" width:100%; text-align:center">
                                <tr>
                                    <td>
                                        <p style="line-height: 0; color: #ffc652; font-size: 40px; font-weight: bold; padding-left: 10px; color:'. $attendcolor.';" class="percentage" id="attendpercent">'.$finaldata[0][$y]->attend.'%</p>
                                        <p style="line-height: 0; color: #BDC2C3;" class="data">Attendance</p>
                                    </td>
                                    <td>
                                        <p style="line-height: 0; color: #ffc652; font-size: 40px; font-weight: bold; padding-left: 10px; color:'.$examcolor.';" class="percentage" id="exampercent">'.$finaldata[0][$y]->exammark.'%</p>
                                        <p style="line-height: 0; color: #BDC2C3;" class="data">Exam Mark</p>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
            <tr>
              <td
                style="
                  font-size: 16px;
                  margin: 0 0 30px 0;
                  padding: 0 20px 0 40px;
                "
              >
                <p style="color: #ffa24d">'.$finaldata[0][$y]->c_name.'</p>
                <p
                  style="
                    color: #fff;
                    line-height: 2.5;
                    text-decoration: underline;
                    text-underline-offset: 10px;

                  "
                  class="information"
                  id = "info"
                >'.
                    $text
                  .'
                </p>
              </td>
            </tr>

            <!-- Sign -->
            <tr>
                <td id="sign" style="display:' .$signdisplay. ';">
                  <ul style="list-style-type: none; margin-top: 25px">
                    <li>
                      <img
                        src='.'https://myschoolstorage.sgp1.digitaloceanspaces.com/emailLogo%2FLinnKoKo_Sign%201.png'.'
                        style="width: 70px; height: 55px"
                        alt=""
                    />
                    </li>
                    <li style="color: #fff">Linn Ko Ko</li>
                  </ul>
                </td>
            </tr>
            </table>
        </td>
      </tr>
    </table>
            ';

            }



            $finaldata = [];


            Mail::to($attendance[0]->email)->send(new ReportCard($maintable,$month));
            $maintable = "";

        }
    }



}
