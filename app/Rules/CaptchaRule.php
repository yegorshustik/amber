<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ReCaptcha\ReCaptcha;

class CaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));
        $resp = $recaptcha->verify($value, request()->ip());

        if (! $resp->isSuccess()) {
            $fail('Something is wrong. Please try again.');
        }
    }
}
