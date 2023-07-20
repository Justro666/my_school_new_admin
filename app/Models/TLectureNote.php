<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TLectureNote extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function mvideos()
    {
        return $this->belongsTo(MVideo::class);
    }

    use HasFactory;
}
