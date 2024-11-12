<?php

namespace App\Http\Controllers\Api;

use App\Events\EmailUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseUserController extends Controller
{
    use ResponseTrait;

    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function show()
    {
        $user = $this->user->load('socialAccounts');

        return $this->returnData(true, __('success.user.show'), 'user', new UserResource($user));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $oldEmail = $this->user->email;

            $this->user->update([
                'full_name' => ($request->first_name ?? $this->user->first_name) . ' ' . ($request->last_name ?? $this->user->last_name),
                'email' => $request->email ?? $this->user->email,
            ]);

            if ($request->email && $request->email !== $oldEmail) {
                $this->user->update([
                    'email_verified_at' => null,
                    'last_verification_attempt_at' => null,
                    'verification_attempts' => 0,
                ]);

                if ($this->user->socialAccounts) {
                    $this->user->socialAccounts()->delete();
                    $this->user->load('socialAccounts');
                }

                $newEmail = $request->email;

                event(new VerifyEmail($this->user));
                event(new EmailUpdated($this->user, $oldEmail, $newEmail));
                DB::commit();

                return $this->returnData(true, __('success.user.profile_update_with_email'), 'user', new UserResource($this->user));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update profile for user: " . $e->getMessage());

            return $this->returnError(__('errors.unexpected_error'), 500);
        }

        return $this->returnData(true, __('success.user.profile_update'), 'user', new UserResource($this->user));
    }

    public function setPassword(SetPasswordRequest $request)
    {
        if (!is_null($this->user->password)) {
            return $this->returnError(__('errors.user.password_already_added'), 409);
        }

        $this->user->password = Hash::make($request->password);
        $this->user->save();

        return $this->returnSuccess(__('success.user.password_add'), 201);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        if (!Hash::check($request->current_password, $this->user->password)) {
            return $this->returnError(__('errors.user.password_current_incorrect'), 401);
        }

        $this->user->password = Hash::make($request->new_password);
        $this->user->save();

        return $this->returnSuccess(__('success.user.password_update'));
    }
}
