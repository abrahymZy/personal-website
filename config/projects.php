<?php

return [

    /*
    |--------------------------------------------------------------------------
    | صفحة المشاريع
    |--------------------------------------------------------------------------
    |
    | تُبنى صفحة المشاريع من واجهة GitHub العامة لمستودعات صاحب الحساب.
    | النتيجة تُحفظ في التخزين المؤقت لتفادي حدود الواجهة البرمجية.
    |
    */

    'owner' => 'abrahymZy',

    'cache_minutes' => 60,

    /*
    |--------------------------------------------------------------------------
    | تصنيف المستودعات
    |--------------------------------------------------------------------------
    |
    | المفاتيح هي أسماء المستودعات على GitHub (ليست عناوين).
    | tier: featured — أنظمة متكاملة | lab — مستودعات وتجارب.
    | title: العنوان المعروض في البطاقة (يُترك فارغاً لاستخدام اسم المستودع).
    |
    */

    'repos' => [
        'smart-expense-app' => ['tier' => 'featured', 'title' => 'Smart Expense App'],
        'hotel_booking' => ['tier' => 'featured', 'title' => 'Hotel Booking'],
        'hotel_booking_flutter_app' => ['tier' => 'featured', 'title' => 'Hotel Booking — Flutter'],
        'phsycal_checkup' => ['tier' => 'featured', 'title' => 'Physical Checkup'],
        'phsycal_checkup_flutter_app' => ['tier' => 'featured', 'title' => 'Physical Checkup — Flutter'],
        'Hussam_Shop' => ['tier' => 'featured', 'title' => 'Bin Anwar Shop'],
        'face_detection' => ['tier' => 'featured', 'title' => 'Face Recognition'],
        'classification_melanoma' => ['tier' => 'featured', 'title' => 'Melanoma Classification'],

        'machine-learning-algorithms-from-with-python' => ['tier' => 'lab', 'title' => 'ML Algorithms with Python'],
        'machine-learning-algorithms-from-scratch-whit-python' => ['tier' => 'lab', 'title' => 'ML Algorithms from Scratch'],
        'roma-pharmacy-index' => ['tier' => 'lab', 'title' => 'Roma Pharmacy — Index'],
        'roma-pharmacym' => ['tier' => 'lab', 'title' => 'Roma Pharmacy'],
        'Ayman-jamel' => ['tier' => 'lab', 'title' => 'Ayman Jamel'],
        'Notes_app' => ['tier' => 'lab', 'title' => 'Notes App'],
    ],

];
