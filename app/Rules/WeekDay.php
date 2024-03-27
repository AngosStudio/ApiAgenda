<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WeekDay implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validate = (new \Carbon\Carbon($value));
        if ($validate->isWeekend()) {
            $fail("O campo $attribute não pode ser em final de semana.");
        }
    }
}
