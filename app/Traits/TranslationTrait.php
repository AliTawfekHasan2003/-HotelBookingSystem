<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait TranslationTrait
{
    public function getAttributeTranslation($attribute)
    {
        $lang = App::getLocale();

        return $this->translations()->attribute($attribute)->language($lang)->pluck('value')->first();
    }

    public function translationFilter(Builder $query, $attribute, $value)
    {
        return $query->whereHas('translations', function ($q) use ($attribute, $value) {
            $q->attribute($attribute)->where('value', 'like', "%{$value}%");
        });
    }

    public function translationRule($isRequired, $language, $min)
    {

        $required = $isRequired ? ['required'] : ['nullable'];
        $rule = ['string', 'min:' . $min . ''];

        --$min;
        $rule[] = $language === 'en'
            ? 'regex:/^[\p{Latin}][\p{Latin}\d_., ]{' . $min . ',}$/u'
            : 'regex:/^[\p{Arabic}][\p{Arabic}\d_.، ]{' . $min . ',}$/u';

        return array_merge($required, $rule);
    }
}
