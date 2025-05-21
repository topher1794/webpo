<?php

use stockalignment\Router;

require_once realpath("vendor/autoload.php");


error_reporting(0);

ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

/**
 * 
 */
$router->get('/', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/login', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/Logout', stockalignment\Controller\AuthenticationController::class, 'logOut');


$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');
$router->post('/userAuthen', stockalignment\Controller\AuthenticationController::class, 'userAuthenticate');
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');
$router->get('/getItemFromShopee', stockalignment\Controller\StocksController::class, 'getItemFromShopee');
$router->get('/getAccessToken', stockalignment\Controller\StocksController::class, 'getAccessToken');
$router->get('/getAccessTokenLazada', stockalignment\Controller\StocksController::class, 'getAccessTokenLazada');
$router->get('/refreshLazadaToken', stockalignment\Controller\StocksController::class, 'refreshLazadaToken');
$router->get('/getLazadaItem', stockalignment\Controller\StocksController::class, 'getLazadaItem');

$router->get('/registration', stockalignment\Controller\RegistrationController::class, 'registration');
$router->post('/newRegistration', stockalignment\Controller\RegistrationController::class, 'newRegistration');


$router->get('/dashboard', stockalignment\Controller\StocksController::class, 'dashboard');


/**
 * SYNCHING ACTION
 */

$router->post('/syncviaform', stockalignment\Controller\StocksController::class, 'syncviaform');

$router->get('/newsync', stockalignment\Controller\StocksController::class, 'newsync');



/**
 * Stock Transaction
 */
$router->get('/transactionlogs', stockalignment\Controller\StocksController::class, 'transactionlogs');
$router->post('/stocktransaction', stockalignment\Controller\StocksController::class, 'stocktransaction');


/**
 * MasterList
 */
$router->post('/uploadmaster', stockalignment\Controller\MasterController::class, 'uploadmaster');
$router->post('/getSkus', stockalignment\Controller\MasterController::class, 'getSkus');
$router->get('/sku', stockalignment\Controller\MasterController::class, 'sku');


/**
 * Swagger
 */

$router->get('/swaggerapi', stockalignment\Controller\StocksController::class, 'swaggerapi');

/**
 * Users
*/

$router->get('/users', stockalignment\Controller\MasterController::class, 'userlists');
$router->post('/getUsers', stockalignment\Controller\MasterController::class, 'getUsers');



$router->dispatch();
