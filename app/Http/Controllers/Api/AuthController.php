<?php

namespace App\Http\Controllers\Api;

use App\Events\VerifyEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login(LoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->email_verified_at) {
            return $this->returnError(__('auth.errors.email.unverified'), 401);
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->returnError(__('auth.errors.unauthorized'), 401);
        }

        return $this->returnLoginRefreshSuccess(__('auth.success.login'), 'user', $user, $token);
    }

    public function register(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        event(new VerifyEmail($user));

        return $this->returnSuccess(__('auth.success.register'), 201);
    }

    public function logout()
    {
        Auth::logout();

        return $this->returnSuccess(__('auth.success.logout'));
    }

    public function refresh()
    {
        return $this->returnLoginRefreshSuccess(__('auth.success.refresh'), 'user', Auth::user(), Auth::refresh());
    }

    public function verifyEmail(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return $this->returnError(__('auth.errors.email.unvalid_signature'), 403);
        }

        $user = User::find($id);

        if (!$user) {
            return $this->returnError(__('errors.user.not_found'), 404);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->returnError(__('auth.errors.email.already_verified'), 409);
        }

        $user->markEmailAsVerified();

        return $this->returnSuccess(__('auth.success.email.verified'));
    }

    public function resendVerification(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->returnError(__('errors.user.not_found'), 404);
        }

        if ($user->email_verified_at) {
            return $this->returnError(__('auth.errors.email.already_verified'), 409);
        }

        $maxAttempts = 3;
        $waitingPeriod = 30;

        if ($user->verification_attempts >= $maxAttempts) {
            $lastVerification = Carbon::parse($user->last_verification_attempt_at);
            if (Carbon::now()->diffInMinutes($lastVerification) < $waitingPeriod) {
                return $this->returnError(__('auth.errors.email.many_attempts'), 429);
            }
            $user->verification_attempts = 0;
        }

        event(new VerifyEmail($user));
        $user->verification_attempts++;
        $user->last_verification_attempt_at = Carbon::now();
        $user->save();

        return $this->returnSuccess(__('auth.success.email.resend_verify'));
    }
}
