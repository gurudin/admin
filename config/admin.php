<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Super administrator account. 
    |--------------------------------------------------------------------------
    |
    | Account with super permissions.
    |
    */
    'admin_email' => [
        'admin@admin.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed route. 
    |--------------------------------------------------------------------------
    |
    | Routes that do not require permission detection.
    |
    */
    'allow' => [
        ['method' => 'get', 'uri' => '/admin/select'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Extends blade
    |--------------------------------------------------------------------------
    |
    | Extends parent class template.
    |
    | Menu item: Gurudin\Admin\Support\Helper::authMenu(Auth::user(), request()->group);
    |
    | Blade example:
    | <head>
    |   <title>@yield('title') {{ config('app.name', '') }}</title>
    |   @yield('style')
    | </head>
    | 
    | <body>
    | 
    |   ...
    |   ...
    | 
    | @yield('script')
    | </body>
    |
    */
    'extends_blade' => 'admin::layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Welcome page loading view.
    |--------------------------------------------------------------------------
    |
    | Welcome page loading view.
    |
    */
    'welcome_view' => 'admin::welcome',

    /*
    |--------------------------------------------------------------------------
    | Select group page loading view.
    |--------------------------------------------------------------------------
    |
    | Select group page loading view.
    |
    | $grop_list = [
    |   ['id' => 1, 'name' => 'Default', 'description' => 'default'],
    |   ...
    | ];
    |
    */
    'select_view' => 'admin::select',

    /*
    |--------------------------------------------------------------------------
    | 403 Forbidden loading view.
    |--------------------------------------------------------------------------
    |
    | View loaded when there is no permission.
    |
    | example: 'forbidden_view' => 'error.403',
    |
    */
    'forbidden_view' => '',
];
