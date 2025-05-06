<?php

use stockalignment\Router;

require_once realpath("vendor/autoload.php");


$router = new Router();

/**
 * 
 */
$router->get('/', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/login', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');
$router->post('/userAuthen', stockalignment\Controller\AuthenticationController::class, 'userAuthenticate');
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');
$router->get('/getItemFromShopee', stockalignment\Controller\StocksController::class, 'getItemFromShopee');
$router->get('/getAccessToken', stockalignment\Controller\StocksController::class, 'getAccessToken');

$router->get('/getAccessTokenLazada', stockalignment\Controller\StocksController::class, 'getAccessTokenLazada');

$router->get('/registration', stockalignment\Controller\RegistrationController::class, 'registration');
$router->post('/newRegistration', stockalignment\Controller\RegistrationController::class, 'newRegistration');




$router->dispatch();
