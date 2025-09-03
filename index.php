<?php

use webpo\Router;

use webpo\Controller\SecurityController;
use webpo\Registry;

require_once realpath("vendor/autoload.php");


$WPA_PATH =  getenv("WPA_PATH");
$ENV_WPA_KEY =  getenv("ENV_WPA_KEY");


if (!isset($WPA_PATH) || !is_string($WPA_PATH) || trim($WPA_PATH) === '') {
     echo 'Error: Path is invalid.';
    exit; 
}

if (!isset($ENV_WPA_KEY) || !is_string($ENV_WPA_KEY) || trim($ENV_WPA_KEY) === '') {
     echo 'Error: Invalid Key.';
    exit; 
}


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


define('BASE_URL', '/webpo' . $BASE_URLQAS);
define('SUFFIX_QAS', $SUFFIX_QAS);
define('BASE_URLQAS', $BASE_URLQAS);
define('WPA_PATH', $WPA_PATH);
define('ENV_WPA_KEY', $ENV_WPA_KEY);
define('GCP_PROJ', "my-dashboard-project-2025");
define('GCP_BUCKET', "ux_phpproject");


$PROJECT_TITLE  =  "WEB P.O AUTOMATION";
$PROJECT_YEAR  =  "2025";

Registry::set('PROJECT_TITLE', $PROJECT_TITLE);
Registry::set('PROJECT_YEAR', $PROJECT_YEAR);

if(isset($_SESSION)) {
    Registry::set('PLANTCODE', $_SESSION["plantcode"]);
}



if(!file_exists($WPA_PATH . "/private.pem")) {
      $clsSecurity = new SecurityController();
      $clsSecurity->generatePublicKey();
}


error_reporting(1);
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

/**
 * Login
 */
// Authentication Controllers
$router->get('/',  webpo\Controller\AuthenticationController::class, 'index');
$router->get('/login', webpo\Controller\AuthenticationController::class, 'index');
$router->get('/Logout', webpo\Controller\AuthenticationController::class, 'logOut');
$router->post('/userAuthen', webpo\Controller\AuthenticationController::class, 'userAuthenticate');


$router->get('/newPo',  webpo\Controller\PoController::class, 'newPo');
$router->post('/getAcctName', webpo\Controller\PoController::class, 'getAcctName');

$router->get('/getKey',  webpo\Controller\SecurityController::class, 'getKey');
$router->get('/dashboard',  webpo\Controller\PoController::class, 'dashboard');


/**
 * UPLOAD 
*/
$router->get('/uploadToGCS',  webpo\Controller\FileController::class, 'uploadToGCS');


$router->dispatch();