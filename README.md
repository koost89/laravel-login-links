# (One time) User login links for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/koost89/laravel-login-links.svg?style=flat-square)](https://packagist.org/packages/koost89/laravel-login-links)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Tests%20-%20Current/main?label=tests)](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Tests%20-%20Current/main?label=tests)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Check%20&%20fix%20styling/main?label=code%20style)](https://img.shields.io/github/workflow/status/koost89/laravel-login-links/Check%20&%20fix%20styling/main?label=code%20style)
[![Total Downloads](https://img.shields.io/packagist/dt/koost89/laravel-login-links.svg?style=flat-square)](https://packagist.org/packages/koost89/laravel-login-links)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require koost89/laravel-login-links
```


The migrations are needed to make sure the generated URLs are only visitable once.
This is done by creating a record for the generated URL and deleting it once the user is logged in.
This flow is highly recommended.

If you do not want the URL to expire after a single login, you can edit the config file supplied with this package.
Simply set `'expire_after_visit'` to `false` in the config supplied with this package.


You can publish the migration file with:
```bash
php artisan vendor:publish --provider="Koost89\UserLogin\LoginLinkServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Koost89\UserLogin\LoginLinkServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /**
     * In this file you can configure parts of login-links.
     * Login links uses Laravels built in signedRoute functionality to generate signedURLs for users.
     */
    'route' => [
        /*
         * Here you can specify the path which is used to generate and authenticate the user on.
         */
        'path' => '/uli',

        /*
         * The amount (in seconds) it takes for the generated URL to expire.
         * Afterwards it simply returns a 404 for the user.
         */
        'expiration' => 60 * 2,

        /*
         * The path the user gets redirected to after the login has finished.
         */
        'redirect_after_login' => '/',

        /*
         * If the token should expire after the first visit.
         * If set to true you must run the migration for the one_time_logins table.
         * If set to false the link can be used until it is expired.
         */
        'expire_after_visit' => true,

        /*
         * Extra middleware you would like to add to the route
         */
        'additional_middleware' => [
            // Middleware\AdminUser::class,
        ]
    ],

    'auth' => [
        /*
         * If you are using a different guard, you can specify it below.
         */
        'guard' => 'web',

        /*
         * Dictates if the application should remember the user.
         */
        'remember' => false,
    ]
];
```

## Usage

```php
$user = User::first();

// Normal class usage
$userLogin = new Koost89\UserLogin();
$url = $userLogin->create($user->id);

// You can also use the Facade

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
