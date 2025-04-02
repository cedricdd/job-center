<?php

namespace App\Rules;

use Closure;
use App\Models\Employer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class OwnerEmployer implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employer = Employer::select('id', 'user_id')->with('user')->find($value);

        if($employer == null || !$employer->user->is(Auth::user())) {
            $fail('The selected company is invalid.');
            return;
        }
    }
}
