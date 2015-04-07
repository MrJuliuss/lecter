# Lecter

A flat file wiki built with Laravel 5 and Bootstrap 3.

<img src="https://raw.githubusercontent.com/MrJuliuss/lecter/master/screenshot.png"/>

## Install

PHP 5.5+ and Composer are required.

To get the latest version on Lecter, add the follow line to the require block of your composer.json file :

```"mrjuliuss/lecter": "0.1.*"```

Launch `composer install` or `composer update`.

Open your `config/app.php` and add the following to the `providers` key :

`'MrJuliuss\Lecter\LecterServiceProvider'`

Open your `app/Http/Kernel.php` and add the following to the `$routeMiddleware` array :

`'lecter.guest' => 'MrJuliuss\Lecter\Http\Middleware\RedirectIfAuthenticated'`

Publish package vendor :

`php artisan vendor:publish`

Go to your app url.

## Config

Lecter has 2 modes:

- Public mode:

Everybody can see the wiki, and you need to modify directly the markdown files. In `config/lecter.php` set the `private` key to `false`.

- Private mode:

Lecter can be a light private wiki build with the basic Laravel 5 authentication system.

Just run `php artisan migrate` to add the basic users table in your databases if doesn't exists. (If you did a `php artisan fresh` before, the migration will not exists)

In `config/lecter.php` set the `private` key to `false`.

#### App name :

In `config/lecter.php` change `app_name` key to set the app name.

#### Lecter location :

Lecter is by default available to `http://your.app`. To change the location of lecter, set the `uri`.

Example : with `uri` setted to `mywiki`, Lecter is available to `http://your.app/mywiki/`

## License

Lecter is licensed under the [MIT License](https://github.com/MrJuliuss/lecter/blob/master/LICENSE).
