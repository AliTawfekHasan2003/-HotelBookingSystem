<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomTypeServiceRequest;
use App\Models\RoomTypeService;
use App\Traits\ResponseTrait;

class RoomTypeServiceController extends Controller
{
    use ResponseTrait;

    public function store(RoomTypeServiceRequest $request)
    {
        $isExists = RoomTypeService::findByIds($request->room_type_id, $request->service_id)->exists();

        if ($isExists) {
            return $this->returnError(__('errors.room_type_service.already_assign'), 409);
        }

        RoomTypeService::create([
            'room_type_id' => $request->room_type_id,
            'service_id' => $request->service_id,
        ]);

        return $this->returnSuccess(__('success.room_type_service.assign'), 201);
    }

    public function destroy(RoomTypeServiceRequest $request)
    {
        $roomTypeService = RoomTypeService::findByIds($request->room_type_id, $request->service_id)->first();

        if (!$roomTypeService) {
            return $this->returnError(__('errors.room_type_service.not_assign'), 409);
        }

        $roomTypeService->forceDelete();

        return $this->returnSuccess(__('success.room_type_service.revoke'));
    }
}
