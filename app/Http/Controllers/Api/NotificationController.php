<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ResponseTrait;

    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function getAllNotifications()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->returnPaginationData(true, __('success.notification.get_all_notifications'), 'notifications', NotificationResource::collection($notifications));
    }

    public function getUnreadNotifications()
    {
        $unreadNotifications = $this->user->unreadNotifications()->paginate(20);

        return $this->returnPaginationData(true, __('success.notification.get_unread_notifications'), 'unreadNotifications', NotificationResource::collection($unreadNotifications));
    }

    public function markAsRead($id)
    {
        $isRead = $this->user->unreadNotifications()->where('id', $id)->update(['read_at' => Carbon::now()]);

        if ($isRead === 1) {
            return $this->returnSuccess(__('success.notification.markAsRead'));
        }

        return $this->returnError(__('errors.notification.not_found_unread_notification'), 404);
    }

    public function markAllAsRead()
    {
        $countReadNotifications = $this->user->unreadNotifications()->update(['read_at' => Carbon::now()]);;

        if ($countReadNotifications > 0) {
            return $this->returnSuccess(__('success.notification.markAllAsRead'));
        }

        return $this->returnError(__('errors.notification.not_found_unread_notifications'), 404);
    }
}
