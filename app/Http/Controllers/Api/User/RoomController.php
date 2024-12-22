<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseRoomController;
use App\Models\Room;
use App\Traits\ResponseTrait;
use App\Http\Resources\RoomResource;
use App\Models\Favorite;

class RoomController extends BaseRoomController
{
    use ResponseTrait;

    public function getFavorite()
    {
        $rooms = Room::with(['translations', 'favorites'])->whereHas('favorites', function ($query) {
            return $query->byUser(auth()->id());
        })->paginate(10);

        if ($rooms->isEmpty()) {
            return $this->returnError(__('errors.room.not_found_favorite'), 404);
        }

        return  $this->returnPaginationData(true, __('success.room.favorite'), 'rooms', RoomResource::collection($rooms));
    }

    public function markAsFavorite($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        $checkInFavorite = Favorite::checkInFavorite($room);
        if ($checkInFavorite) {
            return $this->returnError(__('errors.room.already_favorite'), 409);
        }

        Favorite::addFavorite($room);

        return $this->returnSuccess(__('success.room.add_to_favorite'), 201);
    }

    public function unmarkAsFavorite($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        $checkInFavorite = Favorite::checkInFavorite($room);
        if (!$checkInFavorite) {
            return $this->returnError(__('errors.room.not_in_favorite'), 409);
        }

        Favorite::destroyFavorite($room);

        return $this->returnSuccess(__('success.room.delete_from_favorite'));
    }
}
