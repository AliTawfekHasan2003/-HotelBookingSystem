<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\ResponseTrait;
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
}
