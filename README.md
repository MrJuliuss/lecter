# Lecter

A flat file wiki built with Laravel 5 and Bootstrap 3.

## Install

PHP 5.5+ and Composer are required.

To get the latest version on Lecter, add the follow line to the require block of your composer.json file :

```"mrjuliuss/lecter": "0.1.*"```

Launch `composer install` or `composer update`.

Open your `app/config.php` and add the following to the `providers` key :

`'MrJuliuss\Lecter\LecterServiceProvider'`

Open your `app/Http/Kernel.php` and add the following to the `$routeMiddleware` array :

`'lecter.guest' => 'MrJuliuss\Lecter\Http\Middleware\RedirectIfAuthenticated'`

Publish package vendor :

`php artisan vendor:publish`

Go to your app url.

## Config

Coming soon

## License

Lecter is licensed under the [MIT License](https://github.com/MrJuliuss/lecter/blob/master/LICENSE).
