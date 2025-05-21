<?php

namespace stockalignment;

use stockalignment\Core\Database;
use stockalignment\Controller\AuthenticationController;

// use ccms\Controller\HomeController;
use Exception;
use stockalignment\Model\UserModel;

define('BASE_URL', '/stockalignproj');


class Router
{
    protected $routes = [];

    private function addRoute($route, $controller, $action, $method)
    {

        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    public function dispatch()
    {

        $database = Database::getInstance();

        $pdo =  $database->getPdo();
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method =  $_SERVER['REQUEST_METHOD'];

        $BASE_URL_QAS = "";
        if (strpos($uri, "_qas") !== FALSE) {
            $BASE_URL_QAS = "_qas";
        }

        $uri = str_replace(BASE_URL . $BASE_URL_QAS, "", $uri);

        // print_r($this->routes);
        // print_r( $method);
        // echo $uri;
        // exit();

        if (array_key_exists($uri, $this->routes[$method])) {
            $controller = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];



            /**
             * FILTER TO SKIP SESSION
             */
            $arrayUri = explode("/", $_SERVER['REQUEST_URI']);
            array_splice($arrayUri, 0, 1);
            $skipSession = array(
                "getStocks",
                "/",
                "userAuthen",
                "getRemittedConsignment",
                "getItemFromShopee",
                "getAccessToken",
                "getAccessTokenLazada",
                "registration",
                "newRegistration",
                "syncviaform",
                "Logout",
                "refreshLazadaToken",
                "getLazadaItem",
                "swaggerapi",
                "updatestockv1",
                "apitokenv1",
                "apirefreshtokenv1",
            );
            // print_r($uri);
            // exit();
            $linkUrl = $arrayUri[1];
            if (!in_array($linkUrl, $skipSession)) {
                // print_r($_SESSION["userno"]) ;
                //     echo "sssss";
                //     exit();

                if (empty($_SESSION["userno"])) {
                    $AuthenticationController = new AuthenticationController();
                    $AuthenticationController->index();
                    exit();
                }
            }

            try {
                $model = null;
                switch ($linkUrl) {
                    case "registration":
                    case "newRegistration":
                        $model = new UserModel($pdo);
                        break;
                    default:
                        $model = null;
                }
                // echo "hhhhh";
                // exit();
                $controller = new $controller($model);
                $controller->$action();
            } catch (Exception $e) {
            }
        } else {
            // echo "No route found for URI: $uri";
            http_response_code(404);
            // $page404 = new AuthenticationController();
            // $page404->page404();
            exit();
        }
    }
}
