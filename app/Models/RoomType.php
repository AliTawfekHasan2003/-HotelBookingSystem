<?php

namespace App\Models;

use App\Traits\TranslationTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class RoomType extends Model
{
    use HasFactory, TranslationTrait, SoftDeletes;

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
        return $this->morphMany(Translation::class, 'translatable')->withTrashed();
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

    public static function filterRoomTypes(Request $request, $trashed = false)
    {
        $query = self::query();
        $query = $trashed ? $query->onlyTrashed()->with('translations') : $query->with('translations');

        $ifCriteria = false;

        if ($request->capacity) {
            $query->capacity($request->capacity);
            $ifCriteria = true;
        }

        if ($request->name) {
            $query->name($request->name);
            $ifCriteria = true;
        }

        if ($request->category) {
            $query->category($request->category);
            $ifCriteria = true;
        }

        return ['query' => $query, 'ifCriteria' =>  $ifCriteria];
    }
}
