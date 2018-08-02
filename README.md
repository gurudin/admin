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

### 使用中间件 ```admin```