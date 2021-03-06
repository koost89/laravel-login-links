![Login Links for Laravel Logo](https://github.com/koost89/laravel-login-links/blob/main/meta/logo.png)

# Generate login links for users

[![Latest Version on Packagist](https://img.shields.io/packagist/v/koost89/laravel-login-links.svg?style=flat-square)](https://packagist.org/packages/koost89/laravel-login-links)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Tests%20-%20Current/main?label=tests)](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Tests%20-%20Current/main?label=tests)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Check%20&%20fix%20styling/main?label=code%20style)](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Check%20&%20fix%20styling/main?label=code%20style)

Login links for Laravel is a package for Laravel 6, 7 and 8
that allows users to easily log in with a (one-time) login URL.

### Quick example
Creating a link for a user works as follows:

```php
use Koost89\LoginLinks\Facades\LoginLink;

$user = User::first();
$link = LoginLink::generate($user);
```

Or you can generate a url with the authenticatable object

```php
$user = User::first();
$link = $user->generateLoginLink();
```

You can also use the command

```bash 
php artisan login-links:generate 1
```

## Installation

You can install the package via composer:

```bash
composer require koost89/laravel-login-links
```

You can publish the migration file with:
```bash
php artisan vendor:publish --provider="Koost89\LoginLinks\LoginLinkServiceProvider" --tag="login-links-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Koost89\LoginLinks\LoginLinkServiceProvider" --tag="login-links-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * In this file you can configure parts of login-links.
     */
    'route' => [
        /**
         * Here you can specify the path which is used to generate and authenticate the user on.
         */
        'path' => '/uli',

        /**
         * The amount (in seconds) it takes for the generated URL to expire.
         */
        'expiration' => 60 * 2,

        /**
         * The path the user gets redirected to after the login has finished.
         */
        'redirect_after_login' => '/',

        /**
         * After how many visits the token should expire
         * After the specified amount visits, the token is immediately deleted from the database.
         */
        'allowed_visits_before_expiration' => 1,

        /**
         * Extra middleware you would like to add to the route
         */
        'additional_middleware' => [
            // Middleware\AdminUser::class,
        ]
    ],

    'auth' => [
        /**
         * If you are using a different guard, you can specify it below.
         */
        'guard' => 'web',

        /**
         * Dictates if the application should remember the user.
         */
        'remember' => false,
    ]
];
```

## Usage

### Default usage

By default Login Links expires the tokens after 120 seconds or after a visit to the URL.
These values can be changed in the config file located in `config/login-links.php`.

To get started generating URL's for your users you must run the migration mentioned above.

In your User (or other authenticatable) class add the `CanLoginWithLink` trait.

```php

use Illuminate\Foundation\Auth\User as Authenticatable;
use Koost89\LoginLinks\Traits\CanLoginWithLink;

class User extends Authenticatable
{
    use CanLoginWithLink;
    
    //...
}

```

After this you can generate signed URL's with the following methods. 

```php
use Koost89\LoginLinks\Facades\LoginLink;

$user = User::first();
$link = LoginLink::generate($user);
```

Or you can generate a url with the authenticatable object

```php
$user = User::first();
$link = $user->generateLoginLink();
```

You can also use the command

```bash 
php artisan login-links:generate 1
```

### Authenticatable classes

Out of the box Login links uses the default `web` guard to generate links for users. 
If you haven't changed this in your application the default installation will work.

#### Custom guard
If your application uses a different guard than the default, you can specify this in the config in the `auth.guard` key.


#### Multiple guards
If your application uses multiple guards with different models, you can change which guard 
should be used for a specific model by override the `getGuardName()` method.

For example:

```php

use Illuminate\Foundation\Auth\User as Authenticatable;
use Koost89\LoginLinks\Traits\CanLoginWithLink;

class User extends Authenticatable
{
    use CanLoginWithLink;

    public function getGuardName(): string
    {
        return 'admin';
    }    
    
    //...
}

```

### Events
Events are dispatched on the following actions for you to listen to:

`Koost89\LoginLinks\Events\LoginLinkGenerated`

This event is fired when the URL is generated.

`Koost89\LoginLinks\Events\LoginLinkUsed`

This event is fired when the user is logged in after clicking on the link.


### Commands
Login Links comes with a set of commands that will allow you to manage your links. 

#### Generate Links
The `login-links:generate` command takes an ID and an optional class (which by default is set to "App\Models\User") and returns 
the generated URL for you.

If you have a different authenticatable class instead of the default you can specify it with the `--class=` option. 
For example:
```bash
php artisan login-links:generate 4 --class="App\Models\Admin"
```

#### Cleanup Links

The `login-links:cleanup` command is helpful to clean up time-expired tokens from your database.
This package creates a record in the database for every URL that has been generated. 
Once they are expired, they cannot be accessed anymore. So they no longer serve any purpose and can be discarded. 
You can add this command in your scheduler for automatic cleanup.
```php
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('login-links:cleanup')->everyFiveMinutes();
    }
```



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kevin Oosterveen](https://github.com/koost89)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
