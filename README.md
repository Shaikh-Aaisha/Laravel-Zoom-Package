# Laravel-Zoom-Package

[![Latest Stable Version](http://poser.pugx.org/phpunit/phpunit/v)](https://packagist.org/packages/phpunit/phpunit) [![Total Downloads](http://poser.pugx.org/phpunit/phpunit/downloads)](https://packagist.org/packages/phpunit/phpunit) [![Latest Unstable Version](http://poser.pugx.org/phpunit/phpunit/v/unstable)](https://packagist.org/packages/phpunit/phpunit) [![License](http://poser.pugx.org/phpunit/phpunit/license)](https://packagist.org/packages/phpunit/phpunit) [![PHP Version Require](http://poser.pugx.org/phpunit/phpunit/require/php)](https://packagist.org/packages/phpunit/phpunit)

## Installation
Require this package, with [Composer](https://packagist.org/), in the root directory of your project.

```bash
$ composer require noorisyslaravel/zoom
```

Add the service provider to `config/app.php` in the `providers` array.

```php
               Noorisyslaravel\Zoom\Providers\LaravelZoomProvider::class,
```

## Configuration

Laravel requires connection configuration. To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="Noorisyslaravel\Zoom\Providers\LaravelZoomProvider"
```

You are free to change the configuration file as needed, but the default expected values are below in config/zoom.php file:

```php
return [
    'api_key' => env('ZOOM_CLIENT_KEY'),
    'api_secret' => env('ZOOM_CLIENT_SECRET'),
    'base_url' => 'https://api.zoom.us/v2/',
    'token_life' => 60 * 60 * 24 * 7, // In seconds, default 1 week
    'authentication_method' => 'jwt', // Only jwt compatible at present but will add OAuth2
    'max_api_calls_per_request' => '5' // how many times can we hit the api to return results for an all() request
];

```

#### Run APIs in Postman

import postman collection via link and run APIs

```
https://api.postman.com/collections/22576705-1d39a521-38be-4650-a30e-38d422ef066f?access_key=PMAT-01GYVCCTZDN7SRWWY0XQKEQGDT
```
