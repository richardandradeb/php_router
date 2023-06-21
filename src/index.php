<?php

include_once('router.php');

use Richard\PhpRouter\Router;

$router = new Router();
$router->setRoute([
    'path' => '/home',
    'methods' => 
        ['GET','POST'],
    'controller' => 'Name'
]);

$router->setRoute([
    'path' => '/article/{newParam}',
    'methods' => 
        ['GET','POST'],
    'controller' => 'articleController'
]);

