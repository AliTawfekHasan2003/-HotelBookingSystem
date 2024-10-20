<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ResponseTrait;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login(LoginRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->returnError('Unauthorized: Password or email is invalid.', 401);
        }

        $user = Auth::user();

        return $this->returnRegisterLoginRefreshSuccess("Login successfully.", 'user', $user, $token);
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

        $token = Auth::login($user);

        return $this->returnRegisterLoginRefreshSuccess("User created successfully.", 'user', $user, $token, 201);
    }

    public function logout()
    {
        Auth::logout();

        return $this->returnSuccess('Logout successfully');
    }

    public function refresh()
    {
        return $this->returnRegisterLoginRefreshSuccess("Refresh successfully.", 'user', Auth::user(), Auth::refresh());
    }
}
