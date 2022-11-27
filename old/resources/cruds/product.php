<?php

return [
    'model' => App\Models\Product::class,

    // searchable fields, if you dont want search feature, remove it
    'search' => ['sku'],

    // Manage actions in crud
    'create' => true,
    'update' => true,
    'delete' => true,

    // If you will set it true it will automatically
    // add `user_id` to create and update action
    'with_auth' => true,

    // Validation in update and create actions
    // It will use Laravel validation system
    'validation' => [
        'sku' => 'required',
        'pro_name' => 'required|min:5',
    ],

    // Write every fields in your db which you want to have a input
    // Available types : "ckeditor", "text", "file", "textarea", "password", "number", "email", "select"
    'fields' => [
        'pro_name' => 'text',
        'price' => 'text',
        'catgory' => 'text',
        'sku' => 'text',
        'image' => 'file'
    ],

    // Where files will store for inputs
    'store' => [
        'image' => 'images/products'
    ],

    // which kind of data should be showed in list page
    'show' => ['sku', 'pro_name','price' , 'catgory' , 'image' ],
];
