<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $result = Invoice::filterInvoices($request, $request->user_id ?? null);

        $query = $result['query'];
        $ifCriteria = $result['ifCriteria'];

        $invoices = $query->paginate(10);

        if ($invoices->isEmpty()) {
            if ($ifCriteria)
                return $this->returnError(__('errors.invoice.not_found_index_with_criteria'), 404);
            else
                return $this->returnError(__('errors.invoice.not_found_index'), 404);
        }

        return  $this->returnPaginationData(true, __('success.invoice.index'), 'invoices', InvoiceResource::collection($invoices));
    }

    public function show($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return $this->returnError('errors.invoice.not_found', 404);
        }

        return $this->returnData(true, __('success.invoice.show'), 'invoice', new InvoiceResource($invoice));
    }

    public function bookings($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return $this->returnError(__('errors.invoice.not_found'), 404);
        }

        $bookings = $invoice->bookings()->paginate(10);

        return $this->returnData(true, __('success.invoice.bookings'), 'bookings', BookingResource::collection($bookings));
    }
}
