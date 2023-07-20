<?php

namespace App\Rules;

use App\Models\MClass;
use Illuminate\Contracts\Validation\Rule;

class VideoDateVali implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $mkstarttime;
    private $mkendtime;
    public function __construct($mkstarttime, $mkendtime)
    {
        $this->mkstarttime = $mkstarttime;
        $this->mkendtime = $mkendtime;
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


        if (strtotime($value) > $this->mkstarttime && strtotime($value) < $this->mkendtime) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return 'Date must be between ' . date("d-m-Y", $this->mkstarttime) . ' and ' . date("d-m-Y", $this->mkendtime);
    }
}
