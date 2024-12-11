<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Api\Admin\RoomTypeController as AdminRoomTypeController;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;

class RoomTypeController extends AdminRoomTypeController
{
    use ResponseTrait, TranslationTrait, ImageTrait;

    public function trashedIndex(Request $request)
    { 
        $result = RoomType::filterRoomTypes($request, true);
        $query = $result['query'];
        $ifCriteria = $result['ifCriteria'];

        $roomTypes = $query->paginate(10);

        if ($roomTypes->isEmpty()) {
            if ($ifCriteria)
                return $this->returnError(__('errors.room_type.not_found_index_trashed_with_criteria'), 404);
            else
                return $this->returnError(__('errors.room_type.not_found_index_trashed'), 404);
        }

        return  $this->returnPaginationData(true, __('success.room_type.index_trashed'), 'roomTypes', RoomTypeResource::collection($roomTypes), 200);
    }

    public function trashedShow($id)
    {
        $roomType = RoomType::onlyTrashed()->find($id);

        if (!$roomType) {
            return $this->returnError(__('errors.room_type.not_found'), 404);
        }

        return $this->returnData(true, __('success.room_type.show_trashed'), 'roomType', new RoomTypeResource($roomType));
    }

    public function trashedRestore($id)
    {
        $roomType = RoomType::onlyTrashed()->with('translations')->find($id);

        if (!$roomType) {
            return $this->returnError(__('errors.room_type.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('restore', $roomType);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.room_type.restore'), 500);
        }
        $roomType->restore();

        return $this->returnSuccess(__('success.room_type.restore'));
    }

    public function trashedForceDelete($id)
    {
        $roomType = RoomType::onlyTrashed()->with('translations')->find($id);

        if (!$roomType) {
            return $this->returnError(__('errors.room_type.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('force', $roomType);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.room_type.force_delete'), 500);
        }
        $this->imageDelete($roomType->image);
        $roomType->forceDelete();

        return $this->returnSuccess(__('success.room_type.force_delete'));
    }
}
