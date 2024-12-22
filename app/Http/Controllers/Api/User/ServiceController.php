<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseServiceController;
use App\Http\Resources\ServiceResource;
use App\Models\Favorite;
use App\Models\Service;
use App\Traits\ResponseTrait;

class ServiceController extends BaseServiceController
{
    use ResponseTrait;

    public function getFavorite()
    {
        $services = Service::with(['translations', 'favorites'])->whereHas('favorites', function ($query) {
            return $query->byUser(auth()->id());
        })->paginate(10);

        if ($services->isEmpty()) {
            return $this->returnError(__('errors.service.not_found_favorite'), 404);
        }

        return  $this->returnPaginationData(true, __('success.service.favorite'), 'services', ServiceResource::collection($services));
    }

    public function markAsFavorite($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->returnError(__('errors.service.not_found'), 404);
        }

        $checkInFavorite = Favorite::checkInFavorite($service);
        if ($checkInFavorite) {
            return $this->returnError(__('errors.service.already_favorite'), 409);
        }

        Favorite::addFavorite($service);

        return $this->returnSuccess(__('success.service.add_to_favorite'), 201);
    }

    public function unmarkAsFavorite($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->returnError(__('errors.service.not_found'), 404);
        }

        $checkInFavorite = Favorite::checkInFavorite($service);
        if (!$checkInFavorite) {
            return $this->returnError(__('errors.service.not_in_favorite'), 409);
        }

        Favorite::destroyFavorite($service);

        return $this->returnSuccess(__('success.service.delete_from_favorite'));
    }
}
