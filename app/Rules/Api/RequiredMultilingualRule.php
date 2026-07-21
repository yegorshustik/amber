<?php

namespace App\Rules\Api;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class RequiredMultilingualRule implements ValidationRule
{
    use ValidatesAttributes;

    private array $errors_list = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($value as $locale => $checked_value) {
            if (! $this->validateRequired($attribute, $checked_value)) {
                $this->errors_list[] = [
                    'attribute' => $attribute,
                    'locale' => $locale,
                ];
            }
        }

        if (count($this->errors_list) > 0) {
            $fail(__('cms.validation.required-multilingual', [
                'attribute' => $attribute,
                'locales' => implode(', ', array_column($this->errors_list, 'locale')),
            ]));
        }
    }
}
