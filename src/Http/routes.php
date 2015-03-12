<?php

$router->get('/', ['as' => 'lecter.index', 'uses' => 'LecterController@getIndex']);
$router->get('{any}', ['as' => 'lecter.index', 'uses' => 'LecterController@getIndex'])->where('any', '.*');
