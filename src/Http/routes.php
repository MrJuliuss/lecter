<?php

$groupConfig = ['prefix' => Config::get('lecter.uri')];

if(Config::get('lecter.private') === true) {
    $groupConfig['middleware'] = 'auth';
}

$router->controllers([
    'auth' => 'AuthController',
]);

$router->group($groupConfig, function($router)
{
    $router->get('/', ['as' => 'lecter.index', 'uses' => 'WikiController@getIndex']);
    $router->get('{any}', ['uses' => 'WikiController@getIndex'])->where('any', '.*');
});
