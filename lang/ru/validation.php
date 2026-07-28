<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'string' => 'Поле :attribute должно быть строкой.',
    'email' => 'Поле :attribute должно быть действительным адресом электронной почты.',
    'boolean' => 'Поле :attribute должно быть true или false.',
    'array' => 'Поле :attribute должно быть массивом.',
    'date' => 'Поле :attribute должно быть корректной датой.',
    'confirmed' => 'Подтверждение поля :attribute не совпадает.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'in' => 'Выбранное значение для :attribute некорректно.',
    'min' => [
        'array' => 'Поле :attribute должно содержать не менее :min элементов.',
        'file' => 'Размер файла :attribute должен быть не менее :min Кб.',
        'numeric' => 'Поле :attribute должно быть не менее :min.',
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'max' => [
        'array' => 'Поле :attribute не должно содержать более :max элементов.',
        'file' => 'Размер файла :attribute не должен превышать :max Кб.',
        'numeric' => 'Поле :attribute не должно быть больше :max.',
        'string' => 'Поле :attribute не должно содержать более :max символов.',
    ],
    'between' => [
        'array' => 'Поле :attribute должно содержать от :min до :max элементов.',
        'file' => 'Размер файла :attribute должен быть от :min до :max Кб.',
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'string' => 'Поле :attribute должно содержать от :min до :max символов.',
    ],
    'size' => [
        'array' => 'Поле :attribute должно содержать :size элементов.',
        'file' => 'Размер файла :attribute должен быть :size Кб.',
        'numeric' => 'Поле :attribute должно быть равным :size.',
        'string' => 'Поле :attribute должно содержать :size символов.',
    ],
    'attributes' => [
        'product_id' => 'товар',
        'quantity' => 'количество',
        'customer_name' => 'имя',
        'customer_email' => 'email',
    ],
];
