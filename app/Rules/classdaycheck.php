<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class classdaycheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $day1=substr($value,0,1);
        $day2=substr($value,1,1);
        $day3=substr($value,2,1);
        $day4=substr($value,3,1);
        $day5=substr($value,4,1);
        $day6=substr($value,5,1);
        $day7=substr($value,6,1); 
        $sum= $day1.$day2.$day3.$day4.$day5.$day6.$day7;
        return ($sum != 0) ? true : false ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Day is required.';
    }
}
