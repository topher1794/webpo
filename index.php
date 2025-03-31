<?php 

use stockalignment\Router;

require_once realpath("vendor/autoload.php");


$router = new Router();

/**
 * 
 */
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');


$router->dispatch();