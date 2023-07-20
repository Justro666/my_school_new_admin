<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class MVideo extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function TLectureNote()
    {
        return $this->hasMany(TLectureNote::class, "video_id");
    }
    public function get_videos($id)
    {
        return  DB::table('m_videos')
            ->leftjoin('t_lecture_notes', 't_lecture_notes.video_id', '=', 'm_videos.id')

            ->selectRaw('m_videos.id AS videoId,m_videos.v_name,m_videos.v_date,COUNT(t_lecture_notes.video_id) AS Lecture')

            ->groupBy('m_videos.id')
            ->where('m_videos.class_id', $id)
            ->get();
    }
}
