<?php

// Arabic messages for the validation rules the public and password forms use. Rules not
// listed here fall back to the framework's English messages.
return [
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون :attribute بريداً إلكترونياً صحيحاً.',
    'string' => 'يجب أن يكون :attribute نصاً.',
    'in' => 'قيمة :attribute غير صحيحة.',
    'max' => [
        'string' => 'يجب ألا يزيد :attribute عن :max حرفاً.',
    ],
    'min' => [
        'string' => 'يجب ألا يقل :attribute عن :min أحرف.',
    ],
    'confirmed' => 'تأكيد :attribute غير متطابق.',

    'attributes' => [
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'reason' => 'سبب التواصل',
        'message' => 'الرسالة',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
    ],
];
