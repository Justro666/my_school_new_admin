<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class MPrivacyPolicy extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function searchById($id)
    {
        return DB::table('m_privacy_policies')
            ->select(['id','p_title as title','p_description as description','category_id as category','del_flg as publish'])
            ->find($id);
    }

    public function updateData($request, $id)
    {
        $mprivacypolicy = MPrivacyPolicy::find($id);
        $mprivacypolicy->p_title = $request->privacypolicys_title;
        $mprivacypolicy->p_description = $request->privacypolicys_description;
        $mprivacypolicy->category_id = $request->category;
        $mprivacypolicy->updated_by = session()->get('adminId');
        $mprivacypolicy->save();
    }

    public function insertData($request)
    {
        $mprivacypolicy = new MPrivacyPolicy();
        $mprivacypolicy->p_title = $request->privacypolicys_title;
        $mprivacypolicy->p_description = $request->privacypolicys_description;
        $mprivacypolicy->category_id = $request->category;
        $mprivacypolicy->created_by = session()->get('adminId');
        $mprivacypolicy->save();
    }

    public function deleteData($id){
        $mprivacypolicy = MPrivacyPolicy::find($id);
        $mprivacypolicy->del_flg == 0 ? $mprivacypolicy->del_flg = 1 : $mprivacypolicy->del_flg = 0;
        $mprivacypolicy->updated_by = session()->get('adminId');
        $mprivacypolicy->save();
    }
    
}
