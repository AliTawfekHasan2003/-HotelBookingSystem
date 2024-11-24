<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'translatable_id',
        'translatable_type',
        'language',
        'attribute',
        'value',
    ];

    public function translatable()
    {
        return $this->morphTo();
    }

    public static function addTranslation($model, $attribute, $value, $language)
    {
        self::create([
            'translatable_id' => $model->id,
            'translatable_type' => get_class($model),
            'attribute' => $attribute,
            'value' => $value,
            'language' => $language,
        ]);
    }

    public function updateTranslation($value)
    {
        $this->update([
            'value' => $value,
        ]);
    }

    public function scopeAttribute(Builder $query, $attribute)
    {
        return $query->where('attribute', $attribute);
    }

    public function scopeLanguage(Builder $query, $language)
    {
        return $query->where('language', $language);
    }
}
