<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Look & feel customizations
    |--------------------------------------------------------------------------
    |
    | Make it yours.
    |
     */

    // Project name. Shown in the breadcrumbs and a few other places.
    'project_name' => 'qticket',

    // Menu logos
    'logo_lg' => 'qticket',
    'logo_mini' => '',

    // Developer or company name. Shown in footer.
    'developer_name' => 'Panagiotis Messinezis',

    // Developer website. Link in footer.
    'developer_link' => '',

    // Show powered by Laravel Backpack in the footer?
    'show_powered_by' => false,

    // The AdminLTE skin. Affects menu color and primary/secondary colors used throughout the application.
    'skin' => '',
    // Options: skin-black, skin-blue, skin-purple, skin-red, skin-yellow, skin-green, skin-blue-light, skin-black-light, skin-purple-light, skin-green-light, skin-red-light, skin-yellow-light

    // Date & Datetime Format Syntax: https://github.com/jenssegers/date#usage
    // (same as Carbon)
    'default_date_format' => 'd/m/Y',
    'default_datetime_format' => 'd/m/Y H:i',

    /*
    |--------------------------------------------------------------------------
    | Registration Open
    |--------------------------------------------------------------------------
    |
    | Choose whether new users are allowed to register.
    | This will show up the Register button in the menu and allow access to the
    | Register functions in AuthController.
    |
     */

    'registration_open' =>  false,

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
     */

    // The prefix used in all base routes (the 'admin' in admin/dashboard)
    'route_prefix' => 'admin',

    // Set this to false if you would like to use your own AuthController and PasswordController
    // (you then need to setup your auth routes manually in your routes.php file)
    'setup_auth_routes' => false,

    // Set this to false if you would like to skip adding the dashboard routes
    // (you then need to overwrite the login route on your AuthController)
    'setup_dashboard_routes' => false,

    'setup_my_account_routes' => false,

    

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
     */

    // Fully qualified namespace of the User model
    'user_model_fqn' => '\App\User',

    // The guard that protects the Backpack admin panel.
    // If null, the config.auth.defaults.guard value will be used.
    'guard' => 'backpack',

    // The password reset configuration for Backpack.
    // If null, the config.auth.defaults.passwords value will be used.
    'passwords' => '---------------',
];
