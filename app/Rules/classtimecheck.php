<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class classtimecheck implements Rule
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
        $starthour=substr($value,0,2);
        $startminute=substr($value,3,2);
        $endhour=substr($value,5,2);
        $endminute=substr($value,8,2);
        return ($endhour>=$starthour && $endminute>$startminute|| 
                $endhour>$starthour   ) ? true : false ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'End time must be greater than start time.';
    }
}
