<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoriteable_type',
        'favoriteable_id',
    ];

    public function favoriteable()
    {
        return $this->morphTo();
    }

    public static function addFavorite($model)
    {
        Favorite::create([
            'user_id' => auth()->id(),
            'favoriteable_id' => $model->id,
            'favoriteable_type' => get_class($model),
        ]);
    }

    public function scopeByUser(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public static function checkInFavorite($obj)
    {
        return self::byUser(auth()->id())->where('favoriteable_type', get_class($obj))->where('favoriteable_id', $obj->id)->exists();
    }

    public static function destroyFavorite($obj)
    {
        return self::byUser(auth()->id())->where('favoriteable_type', get_class($obj))->where('favoriteable_id', $obj->id)->delete();
    }
}
