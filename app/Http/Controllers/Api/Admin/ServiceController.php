<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseServiceController;
use App\Http\Requests\StoreOrUpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use App\Traits\TranslationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServiceController extends BaseServiceController
{
    use ResponseTrait, TranslationTrait, ImageTrait;

    public function store(StoreOrUpdateServiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $imagePath = $this->imageStore($request->image, 'services');

            $service = Service::create([
                'is_limited' => $request->is_limited,
                'total_units' => $request->total_units ?? 0,
                'is_free' => $request->is_free,
                'daily_price' => $request->daily_price ?? 0.00,
                'monthly_price' => $request->monthly_price ?? 0.00,
                'image' => $imagePath,
            ]);

            $service->translations()->createMany([
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
            Log::error("Failed to create new service:" . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
        DB::commit();

        return $this->returnData(true, __('success.service.create'), 'service', new ServiceResource($service), 201);
    }

    public function update(StoreOrUpdateserviceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $service = Service::with(['translations'])->find($id);
            if (!$service) {
                return $this->returnError(__('errors.service.not_found'), 404);
            }

            if ($request->image) {
                $oldImagePath = $service->image;
                $newImagePath = $this->imageReplace($oldImagePath, $request->image, 'services');
            }

            $isLimited = $request->is_limited ?? $service->is_limited;
            $isFree = $request->is_free ?? $service->is_free;
            $totalUnits = ($isLimited == false)  ? 0 : ($request->total_units ?? $service->total_units);  
            $dailyPrice = ($isFree == true) ? 0.00 : ($request->daily_price ?? $service->daily_price);
            $monthlyPrice = ($isFree == true) ? 0.00 : ($request->monthly_price ?? $service->monthly_price);

            $service->update([
                'is_limited' => $isLimited,
                'total_units' => $totalUnits,
                'is_free' => $isFree,
                'daily_price' => $dailyPrice,
                'monthly_price' => $monthlyPrice,
                'image' => $newImagePath  ?? $service->image,
            ]);

            $translations = [
                'name' => ['en' => $request->name_en, 'ar' => $request->name_ar],
                'category' => ['en' => $request->category_en, 'ar' => $request->category_ar],
                'description' => ['en' => $request->description_en, 'ar' => $request->description_ar],
            ];

            foreach ($translations as $attribute => $languages) {
                foreach ($languages as $language => $value)
                    if ($value) {
                        $translation = $service->translations()->attribute($attribute)->language($language)->first();
                        if ($translation) {
                            $translation->updateTranslation($value);
                        }
                    }
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to update service:" . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
        DB::commit();

        return $this->returnData(true, __('success.service.update'), 'service', new ServiceResource($service));
    }

    public function destroy($id)
    {
        $service = Service::with(['translations', 'favorites'])->find($id);

        if (!$service) {
            return $this->returnError(__('errors.service.not_found'), 404);
        }

        if ($service->roomTypeServices) {
            $service->roomTypeServices()->delete();
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('soft', $service);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.service.soft_delete'), 500);
        }
        $service->favorites()->delete();
        $service->delete();

        return $this->returnSuccess(__('success.service.soft_delete'));
    }
}
