<?php

$groupConfig = ['prefix' => Config::get('lecter.uri')];

if(Config::get('lecter.private') === true) {
    $groupConfig['middleware'] = 'auth';
}

$router->group($groupConfig, function($router)
{
    $router->get('/', ['as' => 'lecter.index', 'uses' => 'WikiController@getIndex']);
    $router->get('{any}', ['as' => 'lecter.index', 'uses' => 'WikiController@getIndex'])->where('any', '.*');
});

$router->controllers([
    'auth' => 'AuthController',
]);