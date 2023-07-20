<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MContact extends Model
{
    use HasFactory;

    public function messageDetail($id)
    {
        $query = DB::table('m_contacts')->where('m_contacts.id',$id)->first();

        return $query;
    }
}
