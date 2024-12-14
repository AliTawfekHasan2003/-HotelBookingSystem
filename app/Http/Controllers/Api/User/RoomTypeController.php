<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseRoomTypeController;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;
use App\Models\Favorite;
use App\Models\RoomType;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomTypeController extends BaseRoomTypeController
{
    use ResponseTrait;

    public function getFavorite()
    {
        $roomTypes = RoomType::with(['translations', 'favorites'])->whereHas('favorites', function ($query) {
            return $query->byUser(auth()->id());
        })->paginate(10);

        if ($roomTypes->isEmpty()) {
            return $this->returnError(__('errors.room_type.not_found_favorite'), 404);
        }

        return  $this->returnPaginationData(true, __('success.room_type.favorite'), 'roomTypes', RoomTypeResource::collection($roomTypes));
    }

    public function markAsFavorite($id)
    {
        $roomType = RoomType::find($id);

        if (!$roomType) {
            return $this->returnError(__('errors.room_type.not_found'), 404);
        }

        $checkInFavorite = Favorite::checkInFavorite($roomType);
        if ($checkInFavorite) {
            return $this->returnError(__('errors.room_type.already_favorite'), 409);
        }

        Favorite::addFavorite($roomType);

        return $this->returnSuccess(__('success.room_type.add_to_favorite'), 201);
    }

    public function unmarkAsFavorite($id)
    {
        $roomType = RoomType::find($id);

        if (!$roomType) {
            return $this->returnError(__('errors.room_type.not_found'), 404);
        }

        $checkInFavorite = Favorite::checkInFavorite($roomType);
        if (!$checkInFavorite) {
            return $this->returnError(__('errors.room_type.not_in_favorite'), 409);
        }

        Favorite::destroyFavorite($roomType);

        return $this->returnSuccess(__('success.room_type.delete_from_favorite'));
    }
}
