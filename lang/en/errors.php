<?php

return [
    'unexpected_error' => "An unexpected error occurred, Please try again later.",
    'user' => [
        'not_found' => "User not found.",
        'not_found_index' => "Users not found.",
        'password_already_added' => "The password has already been added.",
        'password_current_incorrect' => "The current password is incorrect.",
        'role_already_assigned' => "This role has already been assigned to the user.",
    ],

    'notification' => [
        'not_found_unread_notification' => "Unread notification not found.",
        'not_found_unread_notifications' => "Unread notifications not found.",
    ],

    'room_type' => [
        'not_found_index_with_criteria' => "No room types found matching the given criteria.",
        'not_found_index_trashed_with_criteria' => "No trashed room types found matching the given criteria.",
        'not_found_index' => "No room types available at the moment.",
        'not_found_index_trashed' =>  "No trashed room types found.",
        'not_found_favorite' => "The favorites list does not contain any room types.",
        'not_found' => "Room type not found.",
        'already_favorite' => "Room type has already been added to favorite.",
        'not_in_favorite' => "Room type not in favorite.",
        'has_rooms' => "Unable to delete a room type before deleting all the rooms it belongs to.",
        'soft_delete' => "Unable to delete this room type.",
        'restore' => "Unable to restore this room type from deletion.",
        'force_delete' => "Unable to permanently delete this room type.",
    ],

    'room' => [
        'not_found_index_with_criteria' => "No rooms found matching the given criteria.",
        'not_found_index' => "No rooms available at the moment.",
        'not_found_favorite' => "The favorites list does not contain any rooms.",
        'not_found' => "Room not found.",
        'already_favorite' => "Room has already been added to favorite.",
        'not_in_favorite' => "Room not in favorite.",
        'soft_delete' => "Unable to delete this room.",
    ],
];
