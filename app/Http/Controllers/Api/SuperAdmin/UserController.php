<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Requests\AssignRoleRequest;
use App\Models\User;
use App\Traits\ResponseTrait;

class UserController extends AdminUserController
{
    use ResponseTrait;

    public function assignRole(AssignRoleRequest $request, $id)
    {
        $user = User::find($id);
        $role = $request->role;

        if (!$user) {
            return $this->returnError(__('errors.user.not_found'), 404);
        }

        if ($user->checkHasRole($role)) {
            return $this->returnError(__('errors.user.role_already_assigned'), 409);
        }

        $user->role = $role;
        $user->save();

        return $this->returnSuccess(__('success.user.role_assign'));
    }
}
