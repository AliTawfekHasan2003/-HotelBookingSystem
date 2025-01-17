<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Http\Resources\RoomResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BaseRoomController extends Controller
{
  use ResponseTrait;

  public function index(Request $request)
  {
    $result = Room::filterRooms($request);
    $query = $result['query'];
    $ifCriteria = $result['ifCriteria'];

    $rooms = $query->paginate(10);

    if ($rooms->isEmpty()) {
      if ($ifCriteria)
        return $this->returnError(__('errors.room.not_found_index_with_criteria'), 404);
      else
        return $this->returnError(__('errors.room.not_found_index'), 404);
    }

    return  $this->returnPaginationData(true, __('success.room.index'), 'rooms', RoomResource::collection($rooms));
  }

  public function show($id)
  {
    $room = Room::with('translations')->find($id);

    if (!$room) {
      return $this->returnError(__('errors.room.not_found'), 404);
    }

    return $this->returnData(true, __('success.room.show'), 'room', new RoomResource($room));
  }

  public function unavailableDates($id)
  {
    $room = Room::find($id);

    if (!$room) {
      return $this->returnError(__('errors.room.not_found'), 404);
    }

    $unavailableDates = $room->bookings()->getUnavailableDates();

    if ($unavailableDates->isEmpty()) {
      return $this->returnError(__('errors.room.not_found_dates'), 404);
    }

    return $this->returnData(true, __('success.room.unavailable_dates'), 'unavailableDates', $unavailableDates);
  }
}
