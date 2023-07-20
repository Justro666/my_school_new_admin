<?php

namespace App\Http\Requests;

use App\Rules\classdatecheck;
use App\Rules\classdaycheck;
use App\Rules\classtimecheck;
use App\Rules\feecheck;
use Illuminate\Foundation\Http\FormRequest;

class AdminValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'classinformation' =>['required'],
            'instructor'=>['required'],
            'categories'=>['required'],
            'classnames'=>['required'],
            'fees'=>['required',new feecheck()],
            'startdate'=>['required'],
            'enddate'=>['required'],
            'starttime'=>['required'],
            'endtime'=>['required'],
            'classday'=>[new classdaycheck()],
            'datecheck'=>[new classdatecheck()],
            'timecheck'=>[new classtimecheck()],
        ];
    }
}
