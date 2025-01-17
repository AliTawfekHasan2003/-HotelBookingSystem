<?php

namespace App\Http\Controllers\Api\User;

use App\Events\InvoicePaid;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\ConfirmPaymentRequest;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Service;
use App\Traits\CalculateTotalCostTrait;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Throwable;

class BookingController extends Controller
{
    use ResponseTrait, CalculateTotalCostTrait;

    public function  calculateCost(BookingRequest $request)
    {
        $room = Room::with('roomType')->find($request->room_id);
        $servicesId = $request->services;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $roomCost = $this->handelPrice($startDate, $endDate, $room->roomType->monthly_price, $room->roomType->daily_price);
        $servicesCost = 0.00;

        if ($servicesId) {
            $services =  Service::whereIN('id', $servicesId)->get();

            $servicesCost = $this->calculateServicesCost($services, $startDate, $endDate);
        }

        $totalCost = $servicesCost + $roomCost;
        $dates = $this->handelDate($startDate, $endDate);

        $bookingCost = [
            'total_cost' => number_format($totalCost, 2),
            'room_cost' => number_format($roomCost, 2),
            'services_cost' => number_format($servicesCost, 2),
            'count_month' => $dates['countMonth'],
            'count_day' => $dates['countDay'],
        ];

        return $this->returnData(true, __('success.booking.total_cost'), 'bookingCost', $bookingCost);
    }

    public function paymentIntent(BookingRequest $request)
    {
        $room = Room::with('roomType')->find($request->room_id);
        $servicesId = $request->services;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $roomCost = $this->handelPrice($startDate, $endDate, $room->roomType->monthly_price, $room->roomType->daily_price);
        $servicesCost = 0.00;

        if ($servicesId) {
            $services =  Service::whereIN('id', $servicesId)->get();

            $servicesCost = $this->calculateServicesCost($services, $startDate, $endDate);
        }

        $totalCost = $servicesCost + $roomCost;
        $dates = $this->handelDate($startDate, $endDate);

        DB::beginTransaction();

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            $amount = intval($totalCost * 100);

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'description' => 'Room Booking payment',
            ]);

            $invoice  =  Invoice::addInvoice($startDate, $endDate, $dates['countMonth'], $dates['countDay'], $totalCost, $paymentIntent->id);

            Booking::addBooking($invoice->id, $room->roomType->monthly_price, $room->roomType->daily_price, $roomCost, ['type' => 'room', 'id' => $room->id]);

            if ($services->isNotEmpty()) {
                foreach ($services as $service) {
                    Booking::addBooking(
                        $invoice->id,
                        $service->monthly_price,
                        $service->daily_price,
                        $this->handelPrice($startDate, $endDate, $service->monthly_price, $service->daily_price),
                        ['type' => 'service', 'id' => $service->id],
                    );
                }
            }

            $paymentInformation = [
                'payment_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'total_cost' => round($totalCost, 2),
            ];
            DB::commit();

            return $this->returnData(true, __('success.booking.payment_intent'), 'paymentInformation', $paymentInformation, 201);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Error occurred during payment: ' . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
    }

    public function confirmPayment(ConfirmPaymentRequest $request)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::where('payment_id', $request->payment_id)->first();

            if (!$invoice) {
                return $this->returnError(__('errors.invoice.not_found'), 404);
            }

            if ($invoice->status !== 'pending') {
                return $this->returnError(__('errors.invoice.not_pending'), 409);
            }

            $status = $request->payment_status === 'succeeded' ? 'paid' : 'cancelled';

            $invoice->update([
                'status' => $status,
            ]);
            DB::commit();

            if ($status === 'paid') {
                event(new InvoicePaid($invoice));

                return $this->returnSuccess(__('success.booking.payment_confirm'));
            } else {
                return $this->returnError(__('errors.booking.payment_failed'));
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error occurred during confirm payment: ' . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }
    }
}
