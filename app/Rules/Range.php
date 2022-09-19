<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Range implements Rule
{

    public $validNames;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validNames)
    {
        $this->validNames = $validNames;
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
        $elements = explode(',',$value) ?: [];
        $numberOfElements = count( $elements );

        $isCorrectLength = $numberOfElements % 3 === 0 && $numberOfElements > 0;

        $firstElementIsValid = $numberOfElements / 3 === count( array_filter($elements, function($e, $i) {
          return $i % 3 === 0 && in_array($e, $this->validNames);
        }, ARRAY_FILTER_USE_BOTH) );

        $elementsAreNumeric = $numberOfElements - ( $numberOfElements / 3 ) === count( array_filter($elements, function($e, $i) {
          return $i % 3 !== 0 && is_numeric($e);
        }, ARRAY_FILTER_USE_BOTH) );

        return $isCorrectLength && $firstElementIsValid && $elementsAreNumeric;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not a valid range.';
    }
}
