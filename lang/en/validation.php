<?php

return [
    'required' => "This field is required.",
    'string' => "This field must be a string.",
    'integer' => "This field must be a integer.",
    'decimal' => "This field  must be numeric and must have exactly :number decimal places.",
    'email' => "The email address must be a valid Gmail address.",
    'file_image' => "This field must be an image.",
    'min' => [
        'password' => "The password must be at least :min characters long.",
        'string' => "This field must be at least :min characters long.",
        'capacity' => "The capacity must be at least :min person.",
        'price' => "The price must be at least :min.",
    ],

    'max' => [
        'password' => "The password must not exceed :max characters in length.",
        'string' => "This field must not exceed :max characters in length.",
        'capacity' => "The capacity must not exceed :max persone.",
        'price' => "The price must not exceed :max persons.",
        'image' => "The image size must not exceed :max KB.",
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
        'first_name' => "The first name must start with an Arabic or Latin character and can contain Arabic or Latin characters, digits, and underscores(_).",
        'last_name' => "The last name must start with an Arabic or Latin character and can contain Arabic or Latin characters, digits, and underscores(_).",
        'translation_en' => "This field must start with a Latin character and can contain Latin characters, digits, underscores (_), dots (.), and commas (,).",
        'translation_ar' => "This field must start with a Arabic character and can contain Arabic characters, digits, underscores(_), dots(.), and commas(،).",
    ],

    'exists' => [
        'email' => "The provided email does not exist in our records.",
    ],

    'in' => [
        'role' => "The role must be one of the following: [user, admin, super_admin].",
    ],
];
