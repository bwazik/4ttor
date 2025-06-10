<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

'accepted' => 'يجب قبول هذا الخيار.',
'accepted_if' => 'يجب قبول هذا الخيار عندما يكون :other هو :value.',
'active_url' => 'يرجى إدخال رابط صالح.',
'after' => 'يجب أن يكون التاريخ بعد :date.',
'after_or_equal' => 'يجب أن يكون التاريخ بعد أو في نفس يوم :date.',
'alpha' => 'يجب أن يحتوي هذا الحقل على أحرف فقط.',
'alpha_dash' => 'يجب أن يحتوي هذا الحقل على أحرف وأرقام وشرطات فقط.',
'alpha_num' => 'يجب أن يحتوي هذا الحقل على أحرف وأرقام فقط.',
'array' => 'يجب أن يكون هذا الحقل عبارة عن مجموعة.',
'ascii' => 'يجب أن يحتوي هذا الحقل على أحرف وأرقام فقط.',
'before' => 'يجب أن يكون التاريخ قبل :date.',
'before_or_equal' => 'يجب أن يكون التاريخ قبل أو في نفس يوم :date.',
'between' => [
    'array' => 'يجب أن يحتوي هذا الحقل على بين :min و :max عنصر.',
    'file' => 'يجب أن يكون حجم الملف بين :min و :max كيلوبايت.',
    'numeric' => 'يجب أن يكون الرقم بين :min و :max.',
    'string' => 'يجب أن يحتوي النص على بين :min و :max حرف.',
],
'boolean' => 'يجب أن يكون هذا الحقل صحيح أو خطأ.',
'can' => 'القيمة المدخلة غير مسموح بها.',
'confirmed' => 'التأكيد غير مطابق.',
'contains' => 'الحقل مفقود منه قيمة مهمة.',
'current_password' => 'كلمة المرور غير صحيحة.',
'date' => 'يرجى إدخال تاريخ صحيح.',
'date_equals' => 'يجب أن يكون التاريخ هو نفس :date.',
'date_format' => 'يجب أن يتطابق التاريخ مع التنسيق :format.',
'decimal' => 'يجب أن يحتوي هذا الحقل على :decimal منازل عشرية.',
'declined' => 'يجب رفض هذا الخيار.',
'declined_if' => 'يجب رفض هذا الخيار عندما يكون :other هو :value.',
'different' => 'يجب أن يكون هذا الحقل مختلفًا عن :other.',
'digits' => 'يجب أن يحتوي هذا الحقل على :digits أرقام.',
'digits_between' => 'يجب أن يحتوي هذا الحقل على بين :min و :max أرقام.',
'dimensions' => 'أبعاد الصورة غير صالحة.',
'distinct' => 'القيمة المدخلة مكررة.',
'doesnt_end_with' => 'يجب ألا ينتهي هذا الحقل بأحد القيم التالية: :values.',
'doesnt_start_with' => 'يجب ألا يبدأ هذا الحقل بأحد القيم التالية: :values.',
'email' => 'يرجى إدخال بريد إلكتروني صالح.',
'ends_with' => 'يجب أن ينتهي هذا الحقل بأحد القيم التالية: :values.',
'enum' => 'القيمة المدخلة غير صالحة.',
'exists' => 'القيمة المدخلة غير صالحة.',
'extensions' => 'يجب أن يكون الملف ذو امتداد من هذه الأنواع: :values.',
'file' => 'يجب أن يكون هذا الحقل ملفًا.',
'filled' => 'يجب أن يحتوي هذا الحقل على قيمة.',
'gt' => [
    'array' => 'يجب أن يحتوي هذا الحقل على أكثر من :value عنصر.',
    'file' => 'يجب أن يكون حجم الملف أكبر من :value كيلوبايت.',
    'numeric' => 'يجب أن يكون الرقم أكبر من :value.',
    'string' => 'يجب أن يحتوي النص على أكثر من :value حرف.',
],
'gte' => [
    'array' => 'يجب أن يحتوي هذا الحقل على :value عناصر أو أكثر.',
    'file' => 'يجب أن يكون حجم الملف أكبر من أو يساوي :value كيلوبايت.',
    'numeric' => 'يجب أن يكون الرقم أكبر من أو يساوي :value.',
    'string' => 'يجب أن يحتوي النص على أكثر من أو يساوي :value حرف.',
],
'hex_color' => 'يرجى إدخال لون Hexadecimal صالح.',
'image' => 'يجب أن يكون هذا الحقل صورة.',
'in' => 'القيمة المدخلة غير صالحة.',
'in_array' => 'يجب أن يحتوي هذا الحقل على قيمة من :other.',
'integer' => 'يجب أن يكون هذا الحقل عددًا صحيحًا.',
'ip' => 'يجب أن يكون هذا الحقل عنوان IP صالح.',
'ipv4' => 'يجب أن يكون هذا الحقل عنوان IPv4 صالح.',
'ipv6' => 'يجب أن يكون هذا الحقل عنوان IPv6 صالح.',
'json' => 'يجب أن يكون هذا الحقل نص JSON صالح.',
'list' => 'يجب أن يكون هذا الحقل قائمة.',
'lowercase' => 'يجب أن يكون هذا الحقل حروفًا صغيرة.',
'lt' => [
    'array' => 'يجب أن يحتوي هذا الحقل على أقل من :value عنصر.',
    'file' => 'يجب أن يكون حجم الملف أقل من :value كيلوبايت.',
    'numeric' => 'يجب أن يكون الرقم أقل من :value.',
    'string' => 'يجب أن يحتوي النص على أقل من :value حرف.',
],
'lte' => [
    'array' => 'يجب أن يحتوي هذا الحقل على :value عناصر أو أقل.',
    'file' => 'يجب أن يكون حجم الملف أقل من أو يساوي :value كيلوبايت.',
    'numeric' => 'يجب أن يكون الرقم أقل من أو يساوي :value.',
    'string' => 'يجب أن يحتوي النص على أقل من أو يساوي :value حرف.',
],
'mac_address' => 'يجب أن يكون هذا الحقل عنوان MAC صالح.',
'max' => [
    'array' => 'يجب أن يحتوي هذا الحقل على أقل من أو يساوي :max عنصر.',
    'file' => 'يجب أن يكون حجم الملف أقل من أو يساوي :max كيلوبايت.',
    'numeric' => 'يجب أن يكون الرقم أقل من أو يساوي :max.',
    'string' => 'يجب أن يحتوي النص على أقل من أو يساوي :max حرف.',
],
'max_digits' => 'يجب أن يحتوي هذا الحقل على أقل من أو يساوي :max رقم.',
'mimes' => 'يجب أن يكون الملف من نوع: :values.',
'mimetypes' => 'يجب أن يكون الملف من نوع: :values.',
'min' => [
    'array' => 'يجب أن يحتوي هذا الحقل على :min عناصر على الأقل.',
    'file' => 'يجب أن يكون حجم الملف :min كيلوبايت على الأقل.',
    'accepted' => 'لازم تقبل :attribute.',
    'accepted_if' => 'لازم تقبل :attribute لو :other هو :value.',
    'active_url' => ':attribute لازم يكون رابط صحيح.',
    'after' => ':attribute لازم يكون تاريخ بعد :date.',
    'after_or_equal' => ':attribute لازم يكون تاريخ بعد أو زي :date.',
    'alpha' => ':attribute لازم يكون فيه حروف بس.',
    'alpha_dash' => ':attribute لازم يكون فيه حروف، أرقام، شرطة أو شرطة سفلية.',
    'alpha_num' => ':attribute لازم يكون فيه حروف وأرقام بس.',
    'array' => ':attribute لازم يكون عبارة عن مصفوفة.',
    'ascii' => ':attribute لازم يحتوي على حروف ورموز بشكل واحد.',
    'before' => ':attribute لازم يكون تاريخ قبل :date.',
    'before_or_equal' => ':attribute لازم يكون تاريخ قبل أو زي :date.',
    'between' => [
        'array' => ':attribute لازم يحتوي على بين :min و :max عناصر.',
        'file' => ':attribute لازم يكون بين :min و :max كيلو بايت.',
        'numeric' => ':attribute لازم يكون بين :min و :max.',
        'string' => ':attribute لازم يكون بين :min و :max حروف.',
    ],
    'boolean' => ':attribute لازم يكون صح أو غلط.',
    'can' => ':attribute فيه قيمة مش مسموحة.',
    'confirmed' => ':attribute مش متأكد منه صح.',
    'contains' => ':attribute ناقصه قيمة لازمة.',
    'current_password' => 'الباسورد غلط.',
    'date' => ':attribute لازم يكون تاريخ صحيح.',
    'date_equals' => ':attribute لازم يكون نفس تاريخ :date.',
    'date_format' => ':attribute لازم يطابق الفورمات :format.',
    'decimal' => ':attribute لازم يكون فيه :decimal أماكن عشرية.',
    'declined' => ':attribute لازم يكون مرفوض.',
    'declined_if' => ':attribute لازم يكون مرفوض لو :other هو :value.',
    'different' => ':attribute و :other لازم يكونوا مختلفين.',
    'digits' => ':attribute لازم يكون :digits رقم.',
    'digits_between' => ':attribute لازم يكون بين :min و :max أرقام.',
    'dimensions' => ':attribute فيه أبعاد صورة غير صالحة.',
    'distinct' => ':attribute فيه قيمة مكررة.',
    'doesnt_end_with' => ':attribute مش لازم ينتهي بـ :values.',
    'doesnt_start_with' => ':attribute مش لازم يبدأ بـ :values.',
    'email' => ':attribute لازم يكون بريد إلكتروني صحيح.',
    'ends_with' => ':attribute لازم ينتهي بـ :values.',
    'enum' => ':attribute المختار مش صالح.',
    'exists' => ':attribute المختار مش صالح.',
    'extensions' => ':attribute لازم يكون من الامتدادات دي: :values.',
    'file' => ':attribute لازم يكون ملف.',
    'filled' => ':attribute لازم يكون فيه قيمة.',
    'gt' => [
        'array' => ':attribute لازم يكون فيه أكتر من :value عنصر.',
        'file' => ':attribute لازم يكون أكتر من :value كيلو بايت.',
        'numeric' => ':attribute لازم يكون أكتر من :value.',
        'string' => ':attribute لازم يكون أكتر من :value حروف.',
    ],
    'gte' => [
        'array' => ':attribute لازم يكون فيه :value عناصر أو أكتر.',
        'file' => ':attribute لازم يكون أكتر من أو زي :value كيلو بايت.',
        'numeric' => ':attribute لازم يكون أكتر من أو زي :value.',
        'string' => ':attribute لازم يكون أكتر من أو زي :value حروف.',
    ],
    'hex_color' => ':attribute لازم يكون لون صحيح بالـ Hex.',
    'image' => ':attribute لازم يكون صورة.',
    'in' => ':attribute المختار مش صالح.',
    'in_array' => ':attribute لازم يكون موجود في :other.',
    'integer' => ':attribute لازم يكون عدد صحيح.',
    'ip' => ':attribute لازم يكون عنوان IP صالح.',
    'ipv4' => ':attribute لازم يكون عنوان IPv4 صالح.',
    'ipv6' => ':attribute لازم يكون عنوان IPv6 صالح.',
    'json' => ':attribute لازم يكون نص JSON صالح.',
    'list' => ':attribute لازم يكون عبارة عن قائمة.',
    'lowercase' => ':attribute لازم يكون كله بالحروف الصغيرة.',
    'lt' => [
        'array' => ':attribute لازم يكون فيه أقل من :value عنصر.',
        'file' => ':attribute لازم يكون أقل من :value كيلو بايت.',
        'numeric' => ':attribute لازم يكون أقل من :value.',
        'string' => ':attribute لازم يكون أقل من :value حروف.',
    ],
    'lte' => [
        'array' => ':attribute لازم يكون فيه :value عناصر أو أقل.',
        'file' => ':attribute لازم يكون أقل من أو زي :value كيلو بايت.',
        'numeric' => ':attribute لازم يكون أقل من أو زي :value.',
        'string' => ':attribute لازم يكون أقل من أو زي :value حروف.',
    ],
    'mac_address' => ':attribute لازم يكون عنوان MAC صالح.',
    'max' => [
        'array' => ':attribute لازم يحتوي على أقصى عدد :max عناصر.',
        'file' => ':attribute لازم يكون أقل من أو يساوي :max كيلو بايت.',
        'numeric' => ':attribute لازم يكون أقل من أو يساوي :max.',
        'string' => ':attribute لازم يكون أقل من أو يساوي :max حروف.',
    ],
    'max_digits' => ':attribute لازم يحتوي على أقصى عدد :max من الأرقام.',
    'mimes' => ':attribute لازم يكون من نوع ملف: :values.',
    'mimetypes' => ':attribute لازم يكون من نوع ملف: :values.',
    'min' => [
        'array' => ':attribute لازم يحتوي على أقل من :min عناصر.',
        'file' => ':attribute لازم يكون على الأقل :min كيلو بايت.',
        'numeric' => ':attribute لازم يكون على الأقل :min.',
        'string' => ':attribute لازم يكون على الأقل :min حروف.',
    ],
    'min_digits' => ':attribute لازم يحتوي على أقل من :min من الأرقام.',
    'missing' => ':attribute لازم يكون مفقود.',
    'missing_if' => ':attribute لازم يكون مفقود لو :other هو :value.',
    'missing_unless' => ':attribute لازم يكون مفقود إلا لو :other هو :value.',
    'missing_with' => ':attribute لازم يكون مفقود لما :values يكون موجود.',
    'missing_with_all' => ':attribute لازم يكون مفقود لما :values يكونوا موجودين.',
    'multiple_of' => ':attribute لازم يكون مضاعف لـ :value.',
    'not_in' => ':attribute المختار مش صالح.',
    'not_regex' => ':attribute مش في الفورمات الصح.',
    'numeric' => ':attribute لازم يكون رقم.',
    'password' => [
        'letters' => ':attribute لازم يحتوي على حرف واحد على الأقل.',
        'mixed' => ':attribute لازم يحتوي على حرف صغير وكبير.',
        'numbers' => ':attribute لازم يحتوي على رقم واحد على الأقل.',
        'symbols' => ':attribute لازم يحتوي على رمز واحد على الأقل.',
        'uncompromised' => ':attribute ده كان ضمن تسريب بيانات، اختار :attribute تاني.',
    ],
    'present' => ':attribute لازم يكون موجود.',
    'present_if' => ':attribute لازم يكون موجود لو :other هو :value.',
    'present_unless' => ':attribute لازم يكون موجود إلا لو :other هو :value.',
    'present_with' => ':attribute لازم يكون موجود لما :values يكون موجود.',
    'present_with_all' => ':attribute لازم يكون موجود لما :values يكونوا موجودين.',
    'prohibited' => ':attribute مش مسموح بيه.',
    'prohibited_if' => ':attribute مش مسموح لو :other هو :value.',
    'prohibited_unless' => ':attribute مش مسموح إلا لو :other في :values.',
    'prohibits' => ':attribute بيمنع :other من إنه يكون موجود.',
    'regex' => ':attribute مش مكتوب صح.',
    'required' => ':attribute لازم يكون موجود.',
    'required_array_keys' => ':attribute لازم يحتوي على مدخلات لـ: :values.',
    'required_if' => ':attribute لازم يكون موجود لو :other هو :value.',
    'required_if_accepted' => ':attribute لازم يكون موجود لو :other مقبول.',
    'required_if_declined' => ':attribute لازم يكون موجود لو :other مرفوض.',
    'required_unless' => ':attribute لازم يكون موجود إلا لو :other في :values.',
    'required_with' => ':attribute لازم يكون موجود لو :values موجود.',
    'required_with_all' => ':attribute لازم يكون موجود لو :values موجودين.',
    'required_without' => ':attribute لازم يكون موجود لو :values مش موجود.',
    'required_without_all' => ':attribute لازم يكون موجود لو :values مش موجودين.',
    'same' => ':attribute لازم يطابق :other.',
    'size' => [
        'array' => ':attribute لازم يحتوي على :size عناصر.',
        'file' => ':attribute لازم يكون :size كيلو بايت.',
        'numeric' => ':attribute لازم يكون :size.',
        'string' => ':attribute لازم يكون :size حروف.',
    ],
    'starts_with' => ':attribute لازم يبدأ بـ :values.',
    'string' => ':attribute لازم يكون نص.',
    'timezone' => ':attribute لازم يكون منطقة زمنية صحيحة.',
    'unique' => ':attribute ده تم أخده قبل كده.',
    'uploaded' => ':attribute فشل في رفعه.',
    'uppercase' => ':attribute لازم يكون كله حروف كبيرة.',
    'url' => ':attribute لازم يكون رابط صالح.',
    'uuid' => ':attribute لازم يكون UUID صالح.',
    ],



    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
