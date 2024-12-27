<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseRoomTypeController;
use App\Http\Requests\StoreOrUpdateRoomTypeRequest;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use App\Traits\TranslationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RoomTypeController extends BaseRoomTypeController
{
    use ResponseTrait, ImageTrait, TranslationTrait;

    public function store(StoreOrUpdateRoomTypeRequest $request)
    {
        DB::beginTransaction();
        try {
            $imagePath = $this->imageStore($request->image, 'room_types');

            $roomType = RoomType::create([
                'capacity' => $request->capacity,
                'daily_price' => $request->daily_price,
                'monthly_price' => $request->monthly_price,
                'image' => $imagePath,
            ]);

            $roomType->translations()->createMany([
                [
                    'attribute' => 'name',
                    'value' => $request->name_en,
                    'language' => 'en'
                ],
                [
                    'attribute' => 'name',
                    'value' => $request->name_ar,
                    'language' => 'ar'
                ],
                [
                    'attribute' => 'category',
                    'value' => $request->category_en,
                    'language' => 'en'
                ],
                [
                    'attribute' => 'category',
                    'value' => $request->category_ar,
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
            Log::error("Failed to create new room type:" . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
        DB::commit();

        return $this->returnData(true, __('success.room_type.create'), 'rooomType', new RoomTypeResource($roomType), 201);
    }

    public function update(StoreOrUpdateRoomTypeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $roomType = RoomType::with(['translations'])->find($id);
            if (!$roomType) {
                return $this->returnError(__('errors.room_type.not_found'), 404);
            }

            if ($request->image) {
                $oldImagePath = $roomType->image;
                $newImagePath = $this->imageReplace($oldImagePath, $request->image, 'room_types');
            }

            $roomType->update([
                'capacity' => $request->capacity ?? $roomType->capacity,
                'daily_price' => $request->daily_price ?? $roomType->daily_price,
                'monthly_price' => $request->monthly_price ?? $roomType->monthly_price,
                'image' => $newImagePath ?? $roomType->image,
            ]);

            $translations = [
                'name' => ['en' => $request->name_en, 'ar' => $request->name_ar],
                'category' => ['en' => $request->category_en, 'ar' => $request->category_ar],
                'description' => ['en' => $request->description_en, 'ar' => $request->description_ar],
            ];

            foreach ($translations as $attribute => $languages) {
                foreach ($languages as $language => $value)
                    if ($value) {
                        $translation = $roomType->translations()->attribute($attribute)->language($language)->first();
                        if ($translation) {
                            $translation->updateTranslation($value);
                        }
                    }
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to update room type:" . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
        DB::commit();

        return $this->returnData(true, __('success.room_type.update'), 'roomType', new RoomTypeResource($roomType));
    }

    public function destroy($id)
    {
        $roomType = RoomType::with(['translations', 'favorites','rooms','roomTypeServices'])->find($id);

        if (!$roomType) {
            return $this->returnError(__('errors.room_type.not_found'), 404);
        }

        if($roomType->rooms->isNotEmpty())
        {
            return $this->returnError(__('errors.room_type.has_rooms'),409);
        }
        
        $ifSuccess = $this->handelSoftDeletingTranslations('soft', $roomType);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.room_type.soft_delete'), 500);
        }

        if($roomType->roomTypeServices)
        {
           $roomType->roomTypeServices()->delete();
        }

        $roomType->favorites()->delete();
        $roomType->delete();

        return $this->returnSuccess(__('success.room_type.soft_delete'));
    }
}
