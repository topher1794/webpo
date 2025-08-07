<?php

use stockalignment\Router;

require_once realpath("vendor/autoload.php");

/**
 * DEFINING SET-UP HERE
 */
$host = $_SERVER['HTTP_HOST'];
$SUFFIX_QAS = "";
$BASE_URLQAS = "";
if (strpos($host, "_qas") !== FALSE || strpos($host, "localhost") !== FALSE) {
    $SUFFIX_QAS = "_qas";
    $BASE_URLQAS = "_qas";
    //FOR LOCALHOST AND DEVELOPING NOT USING QAS
    if (strpos($host, "localhost") !== FALSE && !strpos($host, "_qas") !== FALSE) {
        $BASE_URLQAS = "";
    }
}
//$SUFFIX_QAS = "";  //# ENABLE THIS TO SKIP QAS SETUP
//$BASE_URLQAS = ""; //# ENABLE THIS TO SKIP QAS SETUP
define('BASE_URL', '/stockalignproj' . $BASE_URLQAS);
define('SUFFIX_QAS', $SUFFIX_QAS);
define('BASE_URLQAS', $BASE_URLQAS);



error_reporting(0);
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

/**
 * Login
 */
// Authentication Controllers
$router->get('/', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/login', stockalignment\Controller\AuthenticationController::class, 'index');
$router->get('/Logout', stockalignment\Controller\AuthenticationController::class, 'logOut');
$router->post('/userAuthen', stockalignment\Controller\AuthenticationController::class, 'userAuthenticate');

// Stock Controllers
$router->get('/getStocks', stockalignment\Controller\StocksController::class, 'getStocks');
$router->get('/dashboard', stockalignment\Controller\StocksController::class, 'dashboard');
$router->get('/getItemFromShopee', stockalignment\Controller\StocksController::class, 'getItemFromShopee');
$router->get('/getAccessToken', stockalignment\Controller\StocksController::class, 'getAccessToken');
$router->get('/getAccessTokenLazada', stockalignment\Controller\StocksController::class, 'getAccessTokenLazada');
$router->get('/refreshLazadaToken', stockalignment\Controller\StocksController::class, 'refreshLazadaToken');
$router->get('/getLazadaItem', stockalignment\Controller\StocksController::class, 'getLazadaItem');
$router->post('/getDetails', stockalignment\Controller\StocksController::class, 'getDetails');
$router->get('/home', stockalignment\Controller\StocksController::class, 'home');

// Registration Controllers
$router->get('/registration', stockalignment\Controller\RegistrationController::class, 'registration');
$router->post('/newRegistration', stockalignment\Controller\RegistrationController::class, 'newRegistration');




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


/**
 * API
 */
$router->post('/updatestockv1', stockalignment\Controller\StocksController::class, 'syncapi');
$router->post('/apitokenv1', stockalignment\Controller\AuthenticationController::class, 'generateToken');
$router->post('/apirefreshtokenv1', stockalignment\Controller\AuthenticationController::class, 'refreshToken');

/**
 * Stock Checking
 */
$router->get('/checkstock', stockalignment\Controller\StocksController::class, 'checkstock');
$router->post('/checkstockqty', stockalignment\Controller\StocksController::class, 'checkstockqty');

/**
 * Settings
 */

$router->get('/settings',  stockalignment\Controller\SettingsController::class, 'settings');
$router->post('/getSettings', stockalignment\Controller\SettingsController::class, 'getSettings');
$router->post('/updateSettings', stockalignment\Controller\SettingsController::class, 'updateSettings');




$router->dispatch();
