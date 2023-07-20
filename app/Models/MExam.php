<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class MExam extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
