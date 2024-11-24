<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait TranslationTrait
{
    public function getAttributeTranslation($attribute)
    {
        $lang = App::getLocale();

        return $this->translations()->attribute($attribute)->language($lang)->pluck('value')->first();
    }
}
