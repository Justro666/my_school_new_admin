<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class classdatecheck implements Rule
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
        $startyear=substr($value,0,4);
        $startmonth=substr($value,5,2);
        $startday=substr($value,8,2);
        $endyear=substr($value,10,4);
        $endmonth=substr($value,15,2);
        $endday=substr($value,18,2);

        return ($endyear>=$startyear && $endmonth>=$startmonth && $endday> $startday || 
                $endyear>=$startyear && $endmonth>$startmonth && $endday>= $startday ||
                $endyear>$startyear && $endmonth>=$startmonth && $endday>= $startday ) ? true : false ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'End date must be greater than start date.';
    }
}
