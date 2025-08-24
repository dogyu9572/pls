<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMaxLength implements ValidationRule
{
    protected $maxLength;

    public function __construct($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // 빈 값은 통과 (required 규칙에서 처리)
        }

        // 한글 글자 수 계산 (한글 1글자 = 3바이트, 영문/숫자 1글자 = 1바이트)
        $charCount = 0;
        $length = strlen($value);
        
        for ($i = 0; $i < $length; $i++) {
            $char = ord($value[$i]);
            if ($char >= 0x80) {
                // 한글이나 다국어 문자 (3바이트)
                $charCount++;
                $i += 2; // UTF-8에서 한글은 3바이트
            } else {
                // 영문, 숫자, 특수문자 (1바이트)
                $charCount++;
            }
        }

        if ($charCount > $this->maxLength) {
            $fail("{$attribute}은(는) 최대 {$this->maxLength}자(한글 기준)까지 입력 가능합니다. 현재 {$charCount}자입니다.");
        }
    }
}
