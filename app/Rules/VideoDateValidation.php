<?php

namespace App\Rules;

use App\Models\MClass;
use Illuminate\Contracts\Validation\Rule;

class VideoDateValidation implements Rule
{

    public $startdate;
    public $enddate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($startdate,$enddate)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

            // dd($this->startdate < strtotime($value) );
           if($this->startdate < strtotime($value)  && $this->enddate > strtotime($value) ){
            return true;
           };
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Class Date  Must Not Between  '.date("m-d-Y",$this->startdate).' And '.date("m-d-Y" ,  $this->enddate ) ;
    }
}
