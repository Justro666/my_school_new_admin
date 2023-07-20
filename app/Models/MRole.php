<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class MRole extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function get_roles()
    {
        return DB::table('m_roles')->where('r_name', 'IN')->get();
    }

    public function page()
    {
        return $this->belongsToMany(MPage::class,"m_role_pages");
    }
}
