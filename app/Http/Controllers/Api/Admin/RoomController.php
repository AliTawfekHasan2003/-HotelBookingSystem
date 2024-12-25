<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseRoomController as ApiBaseRoomController;
use App\Http\Requests\StoreOrUpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use App\Traits\TranslationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RoomController extends ApiBaseRoomController
{
    use ResponseTrait, TranslationTrait, ImageTrait;

    public function store(StoreOrUpdateRoomRequest $request)
    {
        DB::beginTransaction();
        try {
            $imagePath = $this->imageStore($request->image, 'rooms');

            $room = Room::create([
                'room_type_id' => $request->room_type_id,
                'floor' => $request->floor,
                'number' => $request->number,
                'image' => $imagePath,
            ]);

            $room->translations()->createMany([
                [
                    'attribute' => 'view',
                    'value' => $request->view_en,
                    'language' => 'en'
                ],
                [
                    'attribute' => 'view',
                    'value' => $request->view_ar,
                    'language' => 'ar'
                ],
                [
                    'attribute' => 'description',
                    'value' => $request->description_en,
                    'language' => 'en'
                ],
                [
                    'attribute' => 'description',
                    'value' => $request->description_ar,
                    'language' => 'ar'
                ],
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to create new room:" . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
        DB::commit();

        return $this->returnData(true, __('success.room.create'), 'room', new RoomResource($room), 201);
    }

    public function update(StoreOrUpdateRoomRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $room = Room::with('translations')->find($id);
            if (!$room) {
                return $this->returnError(__('errors.room.not_found'), 404);
            }

            if ($request->image) {
                $oldImagePath = $room->image;
                $newImagePath = $this->imageReplace($oldImagePath, $request->image, 'rooms');
            }

            $room->update([
                'room_type_id' => $request->room_type_id ?? $room->room_type_id,
                'floor' => $request->floor ?? $room->floor,
                'number' => $request->number ?? $room->number,
                'image' => $newImagePath ?? $room->image,
            ]);

            $translations = [
                'view' => ['en' => $request->view_en, 'ar' => $request->view_ar],
                'description' => ['en' => $request->description_en, 'ar' => $request->description_ar],
            ];

            foreach ($translations as $attribute => $languages) {
                foreach ($languages as $language => $value)
                    if ($value) {
                        $translation = $room->translations()->attribute($attribute)->language($language)->first();
                        if ($translation) {
                            $translation->updateTranslation($value);
                        }
                    }
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to update room:" . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
        DB::commit();

        return $this->returnData(true, __('success.room.update'), 'room', new RoomResource($room));
    }

    public function destroy($id)
    {
        $room = Room::with(['translations', 'favorites'])->find($id);

        if (!$room) {
            return $this->returnError(__('errors.room.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('soft', $room);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.room.soft_delete'), 500);
        }

        $room->favorites()->delete();
        $room->delete();

        return $this->returnSuccess(__('success.room.soft_delete'));
    }
}
