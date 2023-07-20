<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class MCategory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function searchById($id){
        return DB::table('m_categories')
            ->where('id',$id)
            ->select('id', 'c_name as name', 'c_description as description')
            ->first();
    }

    public function updateData($request, $id)
    {
        $mcategory = MCategory::find($id);
        $mcategory->c_name = $request->category_name;
        $mcategory->c_description = $request->category_description;
        $mcategory->updated_by = session()->get('adminId');
        $mcategory->save();
    }

    public function deleteData($id){
        $mcategory = MCategory::find($id);
        $mcategory->del_flg = 1;
        $mcategory->updated_by = session()->get('adminId');
        $mcategory->save();
    }
}
