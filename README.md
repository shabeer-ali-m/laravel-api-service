<p align="center">


</p>

# A CRUD MVCS pattern create a service command for Laravel 5+
Create a new CRUD service class, controller, request and service interface

# Install
```bash
composer require smart-php/laravel-api-service --dev
```


# Usage
```bash
$ php artisan make:api {name : Create a service class}
```

# Example

## Create a service class
```bash
$ php artisan make:api User
```

```php
<?php
// app/Http/Services/UserService.php

namespace App\Services;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{

}
```