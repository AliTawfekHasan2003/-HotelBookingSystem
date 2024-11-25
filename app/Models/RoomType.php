<?php

namespace App\Models;

use App\Traits\TranslationTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory, TranslationTrait;

    protected $fillable = [
        'image',
        'capacity',
        'daily_price',
        'monthly_price',
    ];

    protected $casts = [
        'daily_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
    ];

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function favorites()
    {
        return $this->MorphMany(Favorite::class, 'favoriteable');
    }

    public function scopeCapacity(Builder $query, $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    public function scopeName(Builder $query, $name)
    {
        return $this->translationFilter($query, 'name', $name);
    }

    public function scopeCategory(Builder $query, $category)
    {
        return $this->translationFilter($query, 'category', $category);
    }

    public function checkInFavorite()
    {
        return $this->favorites()->byUser(auth()->id())->exists();
    }
}
