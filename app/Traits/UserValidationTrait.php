<?php

namespace App\Traits;


trait UserValidationTrait
{

    public function nameRule($isRequired)
    {
        $rule = ['string', 'min:3', 'max:15', 'regex:/^[\p{Arabic}\p{Latin}][\p{Arabic}\p{Latin}\d_]{2,14}$/u'];

        $rule[] = $isRequired ? 'required' : 'nullable';

        return $rule;
    }

    public function emailRule($isRequired, $isUnique, $isRegex)
    {
        $rule = ['string', 'email', 'max:64'];

        $rule[] = $isRequired ? 'required' : 'nullable';
        $rule[] = $isUnique ? 'unique:users,email,' . auth()->id() : 'exists:users,email';
        if ($isRegex) {
            $rule[] = 'regex:/^[a-zA-Z0-9._%+-]{3,}@gmail\.com$/';
        }

        return $rule;
    }

    public function passwordRule($isRegex, $isConfirmed)
    {
        $rule = ['required', 'string', 'min:8', 'max:20'];

        if ($isRegex) {
            $rule[] = 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\*\@\.\-\+])[a-zA-Z0-9\*\@\.\-\+\#\&]{8,20}$/';
        }
        if ($isConfirmed) {
            $rule[] = 'confirmed';
        }

        return $rule;
    }
}
