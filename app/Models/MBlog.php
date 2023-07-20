<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class MBlog extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function searchById($id){
        return DB::table('m_blogs')
            ->where('id',$id)
            ->select('id','b_title as title','b_description as description','b_photo as photo','del_flg as publish')
            ->first();
    }

    public function updateData($request, $image, $id)
    {
        $mblog = MBlog::find($id);
        $mblog->b_title = $request->blog_title;
        $mblog->b_description = $request->blog_description;
        $mblog->b_photo = $image;
        $mblog->updated_by = session()->get('adminId');
        $mblog->save();
    }

    public function insertData($request,$image)
    {
        $mblog = new MBlog();
        $mblog->b_title = $request->blog_title;
        $mblog->b_description = $request->blog_description;
        $mblog->b_photo = $image;
        $mblog->created_by = session()->get('adminId');
        $mblog->save();
    }

    public function deleteData($id){
        $mblog = MBlog::find($id);
        $mblog->del_flg == 0 ? $mblog->del_flg = 1 : $mblog->del_flg = 0;
        $mblog->updated_by = session()->get('adminId');
        $mblog->save();
    }
}
