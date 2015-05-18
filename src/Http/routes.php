<?php

$groupConfig = ['prefix' => Config::get('lecter.uri')];

if (Config::get('lecter.private') === true) {
    $groupConfig['middleware'] = 'auth';
}

$router->controllers([
    'auth' => 'AuthController',
]);

$router->group($groupConfig, function ($router) {
    $router->get('/', ['as' => 'lecter.index', 'uses' => 'WikiController@getIndex']);
    $router->get('{any}', ['uses' => 'WikiController@getIndex'])->where('any', '.*');

    if (Config::get('lecter.private')) {
        $router->delete('{any}', ['as' => 'lecter.delete-content', 'uses' => 'WikiController@deletePage'])->where('any', '.*');
        $router->put('{any}', ['as' => 'lecter.update-content', 'uses' => 'WikiController@editPage'])->where('any', '.*');
        $router->post('{any?}', ['as' => 'lecter.add-content', 'uses' => 'WikiController@addPage'])->where('any', '.*');
    }
});
