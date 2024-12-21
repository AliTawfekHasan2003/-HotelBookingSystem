<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BaseRoomTypeController extends Controller
{
  use ResponseTrait;

  public function index(Request $request)
  {
    $result = RoomType::filterRoomTypes($request);
    $query = $result['query'];
    $ifCriteria = $result['ifCriteria'];

    $roomTypes = $query->paginate(10);

    if ($roomTypes->isEmpty()) {
      if ($ifCriteria)
        return $this->returnError(__('errors.room_type.not_found_index_with_criteria'), 404);
      else
        return $this->returnError(__('errors.room_type.not_found_index'), 404);
    }

    return  $this->returnPaginationData(true, __('success.room_type.index'), 'roomTypes', RoomTypeResource::collection($roomTypes), 200);
  }

  public function show($id)
  {
    $roomType = RoomType::with('translations')->find($id);

    if (!$roomType) {
      return $this->returnError(__('errors.room_type.not_found'), 404);
    }

    return $this->returnData(true, __('success.room_type.show'), 'roomType', new RoomTypeResource($roomType));
  }

  public function rooms($id)
  {
    $roomType = RoomType::with('translations')->find($id);

    if (!$roomType) {
      return $this->returnError(__('errors.room_type.not_found'), 404);
    }

    $rooms = $roomType->rooms()->paginate(10);

    if ($rooms->isEmpty()) {
      return $this->returnError(__('errors.room_type.not_found_rooms'), 404);
    }

    return $this->returnPaginationData(true, __('success.room_type.rooms'), 'rooms', new RoomResource($rooms));
  }
}
