<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Api\Admin\RoomController as AdminRoomController;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;


class RoomController extends AdminRoomController
{
    use ResponseTrait, TranslationTrait, ImageTrait;

    public function trashedIndex(Request $request)
    {
        $result = Room::filterRooms($request, true);
        $query = $result['query'];
        $ifCriteria = $result['ifCriteria'];

        $rooms = $query->paginate(10);

        if ($rooms->isEmpty()) {
            if ($ifCriteria)
                return $this->returnError(__('errors.room.not_found_index_trashed_with_criteria'), 404);
            else
                return $this->returnError(__('errors.room.not_found_index_trashed'), 404);
        }

        return  $this->returnPaginationData(true, __('success.room.index_trashed'), 'rooms', RoomResource::collection($rooms));
    }

    public function trashedShow($id)
    {
        $room = Room::onlyTrashed()->find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        return $this->returnData(true, __('success.room.show_trashed'), 'room', new RoomResource($room));
    }

    public function trashedRestore($id)
    {
        $room = Room::onlyTrashed()->with('translations')->find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('restore', $room);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.room.restore'), 500);
        }
        $room->restore();

        return $this->returnSuccess(__('success.room.restore'));
    }

    public function trashedForceDelete($id)
    {
        $room = Room::onlyTrashed()->with('translations')->find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('force', $room);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.room.force_delete'), 500);
        }
        $this->imageDelete($room->image);
        $room->forceDelete();

        return $this->returnSuccess(__('success.room.force_delete'));
    }

    public function bookings($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        $bookings = $room->bookings()->paginate(10);

        if ($bookings->isEmpty()) {
            return $this->returnError(__('errors.room.not_found_bookings'), 404);
        }

        return $this->returnPaginationData(true, __('success.room.bookings'), 'bookings', BookingResource::collection($bookings));
    }
}
