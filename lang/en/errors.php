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
        'not_found_rooms' => "No rooms found for this type.",
        'not_found_services' => "No services for this type.",
        'already_favorite' => "Room type has already been added to favorite.",
        'not_in_favorite' => "Room type not in favorite.",
        'has_rooms' => "Unable to delete a room type before deleting all the rooms it belongs to.",
        'soft_delete' => "Unable to delete this room type.",
        'restore' => "Unable to restore this room type from deletion.",
        'force_delete' => "Unable to permanently delete this room type.",
    ],

    'room' => [
        'not_found_index_with_criteria' => "No rooms found matching the given criteria.",
        'not_found_index_trashed_with_criteria' => "No trashed rooms found matching the given criteria.",
        'not_found_index' => "No rooms available at the moment.",
        'not_found_index_trashed' =>  "No trashed rooms found.",
        'not_found_favorite' => "The favorites list does not contain any rooms.",
        'not_found' => "Room not found.",
        'already_favorite' => "Room has already been added to favorite.",
        'not_in_favorite' => "Room not in favorite.",
        'soft_delete' => "Unable to delete this room.",
        'restore' => "Unable to restore this room from deletion.",
        'force_delete' => "Unable to permanently delete this room.",
    ],

    'service' => [
        'not_found_index_with_criteria' => "No services found matching the given criteria.",
        'not_found_index_trashed_with_criteria' => "No trashed services found matching the given criteria.",
        'not_found_index' => "No services available at the moment.",
        'not_found_index_trashed' =>  "No trashed services found.",
        'not_found_favorite' => "The favorites list does not contain any services.",
        'not_found' => "Service not found.",
        'not_found_room_types' => "No room types associated with this service were found.",
        'already_favorite' => "Service has already been added to favorite.",
        'not_in_favorite' => "Service not in favorite.",
        'soft_delete' => "Unable to delete this service.",
        'restore' => "Unable to restore this service from deletion.",
        'force_delete' => "Unable to permanently delete this service.",
    ],

    'room_type_service' => [
        'already_assign' => "Service has already been assigned to room type.",
        'not_assign' => "This service not assign to room type.",
    ],
];
