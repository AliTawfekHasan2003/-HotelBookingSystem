<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseUserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;

class UserController extends BaseUserController
{
    use ResponseTrait;

    public function index()
    {
        $users = User::whereNotNull('email_verified_at')->with('socialAccounts')->paginate(20);

        return $this->returnPaginationData(true, __('success.user.index'), 'users', UserResource::collection($users));
    }

    public function showUser($id)
    {
        $user = User::whereNotNull('email_verified_at')->find($id);

        if (!$user) {
            return $this->returnError(__('errors.user.not_found'), 404);
        }

        return $this->returnData(true, __('success.user.show_user'), 'user', new UserResource($user));
    }
}
