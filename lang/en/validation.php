<?php

return [
    'required' => "This field is required.",
    'string' => "This field must be a string.",
    'email' => "The email address must be a valid Gmail address.",
    'min' => [
        'password' => "The password must be at least :min characters long.",
        'string' => "This field must be at least :min characters long.",
    ],

    'max' => [
        'password' => "The password must not exceed :max characters in length.",
        'string' => "This field must not exceed :max characters in length.",
    ],

    'unique' => [
        'email' => "The email address has already been taken.",
    ],

    'confirmed' => [
        'password' => "The password confirmation does not match.",
    ],

    'regex' => [
        'email' => "Please enter a valid email address in the correct format (e.g., user@example.com).",
        'password' => "The password must contain at least one lowercase letter, one uppercase letter, a number, and one of the following symbols: (+-*.@).",
        'first_name' => "The first name must start with an Arabic or Latin character and can contain Arabic or Latin characters, digits, and underscores.",
        'last_name' => "The last name must start with an Arabic or Latin character and can contain Arabic or Latin characters, digits, and underscores.",
    ],

    'exists' => [
        'email' => "The provided email does not exist in our records.",
    ],

    'in' => [
        'role' => "The role must be one of the following: [user, admin, super_admin].",
    ],
];
