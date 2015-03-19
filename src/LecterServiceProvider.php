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
        $this->app->singleton('lecter', function () {
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
        $config = realpath(__DIR__.'/../config/lecter.php');
        $this->mergeConfigFrom($config, 'lecter');

        $this->publishes([
            $config => config_path('lecter.php'),
        ]);

        $this->publishes([
            __DIR__.'/../assets/css/' => public_path('css/mrjuliuss/lecter'),
            __DIR__.'/../assets/jsx/' => public_path('jsx/mrjuliuss/lecter'),
            __DIR__.'/../assets/js/' => public_path('js/mrjuliuss/lecter'),
            __DIR__.'/../assets/img/' => public_path('img/mrjuliuss/lecter'),
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
