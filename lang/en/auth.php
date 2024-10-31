<?php

return [
    'success' => [
        'register' => "Account created, Please check your email to verify your account.",
        'login' => "Logged in successfully.",
        'refresh' => "Session refreshed successfully.",
        'logout' => "Logged out successfully.",
        'email' => [
            'verified' => "Email verified successfully.",
            'resend_verify' => "A verification email has been sent, Please check your email to verify your account."
        ],
    ],
    'error' => [
        'unauthorized' => "Unauthorized: Password or email is invalid.",
        'email' => [
            'unverified' => "Please verify your email, Then log in again.",
            'unvalid_signature' => "Verification link expired, Please request a new one.",
            'already_verified' => "Email is already verified.",
            'many_attempts' => "You have exceeded the maximum number of attempts, Please wait few minutes before trying again."
        ],
        'token_expired' => "Your session has expired, Please log in again.",
        'token_invalid' => "Your session is invalid, Please log in again.",
        'token_not_provided' => "No valid session found, Please log in to continue.",
        'unexpected_error' => "An unexpected error occurred, Please try again later.",
    ],
];
