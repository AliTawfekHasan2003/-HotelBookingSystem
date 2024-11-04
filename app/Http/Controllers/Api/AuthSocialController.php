<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use App\Traits\SocialCallbackTrait;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialController extends Controller
{
    use ResponseTrait, SocialCallbackTrait;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        return $this->handleSocialCallback('google');
    }
    public function redirectToGithub()
    {
        return Socialite::driver('github')->stateless()->redirect();
    }

    public function githubCallback()
    {
        return $this->handleSocialCallback('github');
    }
}
