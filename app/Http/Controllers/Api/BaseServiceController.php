<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceLimitedRequest;
use App\Http\Resources\RoomTypeResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Contracts\Translation\TranslatorTrait;

class BaseServiceController extends Controller
{
  use ResponseTrait, TranslatorTrait;

  public function index(Request $request)
  {
    $result = Service::filterServices($request);
    $query = $result['query'];
    $ifCriteria = $result['ifCriteria'];

    $services = $query->paginate(10);

    if ($services->isEmpty()) {
      if ($ifCriteria)
        return $this->returnError(__('errors.service.not_found_index_with_criteria'), 404);
      else
        return $this->returnError(__('errors.service.not_found_index'), 404);
    }

    return  $this->returnPaginationData(true, __('success.service.index'), 'services', ServiceResource::collection($services));
  }

  public function show($id)
  {
    $service = Service::with('translations')->find($id);

    if (!$service) {
      return $this->returnError(__('errors.service.not_found'), 404);
    }

    return $this->returnData(true, __('success.service.show'), 'service', new ServiceResource($service));
  }

  public function roomTypes($id)
  {
    $service = Service::with('translations')->find($id);

    if (!$service) {
      return $this->returnError(__('errors.service.not_found'), 404);
    }

    $roomTypes = $service->roomTypes()->paginate(10);

    if ($roomTypes->isEmpty()) {
      return $this->returnError(__('errors.service.not_found_room_types'), 404);
    }

    return $this->returnPaginationData(true, __('success.service.room_types'), 'roomTypes',  RoomTypeResource::collection($roomTypes));
  }

  public function limitedUnits(ServiceLimitedRequest $request, $id)
  {
    $service = Service::find($id);

    if (!$service) {
      return $this->returnError(__('errors.service.not_found'), 404);
    }

    if (!$service->is_limited) {
      return $this->returnError(__('errors.service.not_limited'), 409);
    }

    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    $countAvailableServiceUnits = $service->bookings()->countAvailableServiceUnits($startDate, $endDate, $service->total_units);

    return $this->returnData(true, __('success.service.limited_units'), 'countAvailableServiceUnits', $countAvailableServiceUnits);
  }
}
