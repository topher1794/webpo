<?php

use stockalignment\Router;

require_once realpath("vendor/autoload.php");


$router = new Router();

/**
 * 
 */
$router->get('/', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');
$router->post('/userAuthen', stockalignment\Controller\AuthenticationController::class, 'userAuthenticate');
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');

$router->dispatch();
