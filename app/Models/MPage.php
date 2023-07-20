<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MPage extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function role()
    {
        return $this->belongsToMany(MRole::class, "m_role_pages");
    }

    public function insertData($request, $image)
    {

        // dd($request->main);
        $mPage = new MPage();
        $mPage->p_name = $request->page_name;
        $mPage->p_route = strtolower($request->page_route);
        $mPage->p_icon = $image;
        $request->main == "1" ? $mPage->menu = 1 : $mPage->menu = 2;
        $mPage->created_by = session()->get('adminId');
        $mPage->save();

        $pages = MPage::where('p_route', $mPage->p_route)->first();

        $rolePage = new MRolePage();
        $rolePage->m_page_id = $pages->id;
        $rolePage->m_role_id = session()->get('roleId');
        $rolePage->created_by = session()->get('adminId');
        $rolePage->save();
    }

    public function updateData($request, $image, $id)
    {
 
        $mPage = MPage::find($id);
        $mPage->p_name = $request->page_name;
        $mPage->p_route = $request->page_route;
        $mPage->p_icon = $image;
        $mPage->menu = $request->menu;
        $mPage->updated_by = session()->get('adminId');
        $mPage->save();
    }
}
