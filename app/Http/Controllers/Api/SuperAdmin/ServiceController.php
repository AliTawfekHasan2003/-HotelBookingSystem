<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;

class ServiceController extends AdminServiceController
{
    use ResponseTrait, TranslationTrait, ImageTrait;

    public function trashedIndex(Request $request)
    {
        $result = Service::filterServices($request, true);
        $query = $result['query'];
        $ifCriteria = $result['ifCriteria'];

        $services = $query->paginate(10);

        if ($services->isEmpty()) {
            if ($ifCriteria)
                return $this->returnError(__('errors.service.not_found_index_trashed_with_criteria'), 404);
            else
                return $this->returnError(__('errors.service.not_found_index_trashed'), 404);
        }

        return  $this->returnPaginationData(true, __('success.service.index_trashed'), 'services', ServiceResource::collection($services));
    }

    public function trashedShow($id)
    {
        $service = Service::onlyTrashed()->find($id);

        if (!$service) {
            return $this->returnError(__('errors.service.not_found'), 404);
        }

        return $this->returnData(true, __('success.service.show_trashed'), 'service', new ServiceResource($service));
    }

    public function trashedRestore($id)
    {
        $service = Service::onlyTrashed()->with('translations')->find($id);

        if (!$service) {
            return $this->returnError(__('errors.service.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('restore', $service);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.service.restore'), 500);
        }

        $roomTypeServices = $service->roomTypeServices()->onlyTrashed();
        if ($roomTypeServices) {
            $roomTypeServices->restore();
        }
        $service->restore();

        return $this->returnSuccess(__('success.service.restore'));
    }

    public function trashedForceDelete($id)
    {
        $service = Service::onlyTrashed()->with('translations')->find($id);

        if (!$service) {
            return $this->returnError(__('errors.service.not_found'), 404);
        }

        $ifSuccess = $this->handelSoftDeletingTranslations('force', $service);

        if (!$ifSuccess) {
            return $this->returnError(__('errors.service.force_delete'), 500);
        }

        $roomTypeServices = $service->roomTypeServices()->onlyTrashed();
        if ($roomTypeServices) {
            $roomTypeServices->forceDelete();
        }

        $this->imageDelete($service->image);
        $service->forceDelete();

        return $this->returnSuccess(__('success.service.force_delete'));
    }
}
