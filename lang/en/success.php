<?php

return [
    'user' => [
        'password_add' => "The password has been added to your account successfully.",
        'password_update' => "The password has been updated successfully.",
        'profile_update_with_email' => "Your account information has been updated successfully. Please check your new email to confirm it. We also recommend updating your email in any linked social media accounts.",
        'profile_update' => "Your account information has been updated successfully.",
        'show_profile' => "Profile details fetched successfully.",
        'show_user' => "User details fetched successfully.",
        'index' => "Details of all users with verified accounts fetched successfully.",
        'role_assign' => "The role has been assigned succefully.",
    ],

    'notification' => [
        'get_all_notifications' => "All notifications have been fetched successfully.",
        'get_unread_notifications' => "Unread notifications have been fetched successfully.",
        'markAsRead' => "The notification has been read successfully.",
        'markAllAsRead' => "All notifications have been read successfully.",
    ],

    'room_type' => [
        'index' => "Room types fetched successfully.",
        'index_trashed' => "Trashed room types fetched successfully.",
        'show' => "Room type fetched successfully.",
        'show_trashed' => "Trashed room type fetched successfully.",
        'rooms' => "Rooms for this type fetched successfully.",
        'services' => "Services for this type fetched successfully.",
        'favorite' => "Favorite room types fetched successfully.",
        'add_to_favorite' => "Room type has been added to favorite successfully.",
        'delete_from_favorite' => "Room type has been deleted from favorite successfully.",
        'create' => "New room type created successfully.",
        'update' => "Room type updated successfully.",
        'soft_delete' => "Room type deleted successfully.",
        'restore' => "Room type restored from deletion successfully.",
        'force_delete' => "Room type permanently deleted successfully.",
    ],

    'room' => [
        'index' => "Rooms fetched successfully.",
        'index_trashed' => "Trashed rooms fetched successfully.",
        'show' => "Room fetched successfully.",
        'show_trashed' => "Trashed room fetched successfully.",
        'bookings' => "Bookings for this room fetched successfully.",
        'favorite' => "Favorite room types fetched successfully.",
        'unavailable_dates' => "Unavailable dates for this room fetched successfully.",
        'add_to_favorite' => "Room has been added to favorite successfully.",
        'delete_from_favorite' => "Room has been deleted from favorite successfully.",
        'create' => "New room created successfully.",
        'update' => "Room updated successfully.",
        'soft_delete' => "Room deleted successfully.",
        'restore' => "Room restored from deletion successfully.",
        'force_delete' => "Room permanently deleted successfully.",
    ],

    'service' => [
        'index' => "Services fetched successfully.",
        'index_trashed' => "Trashed services fetched successfully.",
        'show' => "Service fetched successfully.",
        'show_trashed' => "Trashed service fetched successfully.",
        'room_types' => "Room types associated with this service fetched successfully.",
        'bookings' => "Bookings for this service fetched successfully.",
        'favorite' => "Favorite services fetched successfully.",
        'unavailable_dates' => "Unavailable dates for this service fetched successfully.",
        'add_to_favorite' => "Service has been added to favorite successfully.",
        'limited_units' => "The number of units available in this service fetched successfully.",
        'delete_from_favorite' => "Service has been deleted from favorite successfully.",
        'create' => "New service created successfully.",
        'update' => "Service updated successfully.",
        'soft_delete' => "Service deleted successfully.",
        'restore' => "Service restored from deletion successfully.",
        'force_delete' => "Service permanently deleted successfully.",
    ],

    'room_type_service' => [
        'assign' => "Service assigned to room Type successfully.",
        'revoke' => "Service revoked from room Type successfully.",
    ],

    'booking' => [
        'total_cost' => 'The total cost for the booking calculate successfully.',
        'payment_intent' => "Payment initiated successfully.",
        'payment_confirm' => "Payment confirmed successfully.",
    ],

    'invoice' => [
        'index_user' => "All your invoices fetched successfully.",
        'index' => "All invoices fetched successfully.",
        'show_user' => "Your invoice fetched successfully.",
        'show' => "The invoice fetched successfully.",
        'bookings_user' => "All bookings for your invoice fetched successfully.",
        'bookings' => "All bookings for this invoice fetched successfully.",
    ],
];
