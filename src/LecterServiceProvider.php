<?php

namespace MrJuliuss\Lecter;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Lecter Service Provider
 *
 * @author Julien Richarte <julien.richarte@gmail.com>
 */
class LecterServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('lecter', function ($app) {
            return new Lecter();
        });

        $this->setupRoutes($this->app->router);
        $this->setupPackage();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    protected function setupPackage()
    {
        $this->publishes([
            __DIR__.'/../assets/css/' => public_path('css/mrjuliuss/lecter'),
        ], 'public');

        $this->loadViewsFrom(realpath(__DIR__.'/../views'), 'lecter');
    }

    /**
     * Setup the routes.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'MrJuliuss\Lecter\Http\Controllers'], function (Router $router) {
            require __DIR__.'/Http/routes.php';
        });
    }
}
