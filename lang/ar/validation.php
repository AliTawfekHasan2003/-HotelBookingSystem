<?php

return [
    'required' => ".هذا الحقل مطلوب",
    'string' => ".هذا الحقل يجب أن يكون نصًا",
    'integer' => ".هذا الحقل يجب أن يكون عدد صحيح",
    'decimal' => ".فواصل عشرية بالضبط :number هذا الحقل يجب أن يكون رقماً و يجب أن يكون هناك",
    'boolean' => ".هذا الحقل يجب أن يكون قيمة منطقية",
    'email' => ".صحيح Gmail يجب أن يكون عنوان البريد الالكتروني",
    'file_image' => ".هذا الحقل يجب أن يكون صورة",
    'min' => [
        'password' => ".محرف :min يجب أن تحتوي كلمة المرور على الأقل",
        'string' => ".محرف :min هذا الحقل يجب أن يحتوي على الأقل",
        'capacity' => ".شخصًا :min يجب أن تكون السعة على الأقل",
        'price' => ".:min يجب أن يكون السعر على الأقل",
        'floor' => ".:min يجب أن يكون الطابق على الأقل",
        'number' => ".:min يجب أن يكون رقم الغرفةعلى الأقل",
        'units' => ".:min يجب أن يكون عدد وحدات الخدمة على الأقل",
    ],

    'max' => [
        'password' => ".محرف :max يجب ألا تتجاوز كلمة المرور",
        'string' => ".محرف :max هذا الحقل يجب ألا يتجاوز",
        'capacity' => ".أشخاص :max يجب ألا تتجاوز السعة ",
        'price' => ".:max يجب ألا يتجاوز السعر ",
        'image' => ".كيلوبايت :max يجب ألا يتجاوز حجم الصورة",
        'floor' => ".:max يجب ألا يتجاوز الطابق",
        'number' => ".:max يجب ألا يتجاوز رقم الغرفة",
    ],

    'unique' => [
        'email' => ".البريد الإلكتروني مستخدم بالفعل",
        'floor_number' => ".يجب ألا يتكرر رقم الغرفة مع نفس الطابق",
    ],

    'confirmed' => [
        'password' => ".يجب أن تتطابق كلمة المرور مع تأكيدها",
    ],

    'regex' => [
        'email' => ".(user@example.com :مثل) يرجى إدخال عنوان بريد إلكتروني صحيح بالصيغة المناسبة ",
        'password' => ".(+-*.@) :يجب أن تحتوي كلمة المرور على الأقل على حرف صغير، وحرف كبير، ورقم، وأحد الرموز التالية",
        'first_name' => ".(_)يجب أن يبدأ الاسم الأول بحرف عربي أو إنجليزي. ويمكن أن يحتوي على أحرف عربية أو إنجليزية، أرقام، وشرطة سفلية",
        'last_name' => ".(_)يجب أن يبدأ اسم العائلة بحرف عربي أو إنجليزي. ويمكن أن يحتوي على أحرف عربية أو إنجليزية، أرقام، وشرطة سفلية",
        'translation_en' =>  ".(,)هذا الحقل يجب أن يبدأ فقط بحرف إنجليزي ويمكن أن يحوي على أحرف إنجليزي، أرقام، شرطة سفلية(_)، نقاط(.)، و فواصل",
        'translation_ar' => ".(،)هذا الحقل يجب أن يبدأ فقط بحرف عربي ويمكن أن يحوي على أحرف عربية، أرقام، شرطة سفلية(_)، نقاط(.)، و فواصل ",
    ],

    'exists' => [
        'email' => ".البريد الإلكتروني المدخل غير موجود في سجلاتنا",
        'room_type_id' => ".نوع الغرفة المدخلة غير موجود في سجلاتنا",
        'service_id' => ".الخدمة المدخلة غير موجودة في سجلاتنا",
    ],

    'in' => [
        'role' => ".[user, admin, super_admin] :الدور يجب أن يكون واحد من التالي",
        'price' => ".:value يجب أن تكون قيمة السعر",
        'units' => ".:value يجب أن يكون عدد وحدات الخدمة",
    ],
];
