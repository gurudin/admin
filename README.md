# laravel-admin

### 安装
```composer require gurudin/admin```

### 配置
修改```config/auth.php``` providers配置

```'model' => Gurudin\Admin\Models\User::class,```

### 迁移数据库表
```php artisan migrate```

### 发布静态文件
```php artisan vendor:publish --tag=gurudin-admin --force```

### 发布配置文件
```php artisan vendor:publish --tag=gurudin-admin-config --force```

### 配置文件
```
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

    /*
    |--------------------------------------------------------------------------
    | Logo uri.
    |--------------------------------------------------------------------------
    |
    */
    'logo_uri' => '/vendor/gurudin/images/logo.png',
];

```

### 使用中间件 ```admin```