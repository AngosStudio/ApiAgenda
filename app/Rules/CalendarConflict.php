<?php

namespace App\Rules;

use App\Models\Calendar;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CalendarConflict implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startDate = $this->data['dt_start'];
        $endDate = $this->data['dt_end'];
        $userCalendars = Calendar::where('user_id', auth()->user()->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('dt_start', [$startDate, $endDate])
                      ->orWhereBetween('dt_end', [$startDate, $endDate]);
            })
            ->count()
        ;

        if ($userCalendars > 0) {
            $fail("O período do agendamento conflita com outros previamente cadastrados.");
        }
    }
}
