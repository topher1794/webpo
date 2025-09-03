<?php

namespace webpo;

use webpo\Core\Database;
use webpo\Controller\AuthenticationController;

// use ccms\Controller\HomeController;
use Exception;
use webpo\Model\UserModel;


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

        $uri = str_replace(BASE_URL . BASE_URLQAS, "", $uri);
      
        if (array_key_exists($uri, $this->routes[$method])) {
            $controller = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];


            /**
             * FILTER TO SKIP SESSION
             */
            $arrayUri = explode("/", $_SERVER['REQUEST_URI']);
            array_splice($arrayUri, 0, 1);
            $skipSession = array(
                "/",
                "userAuthen",
                "getKey",
            );
           
            $linkUrl = $arrayUri[1];
            if (!in_array($linkUrl, $skipSession)) {
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
                        // $model = new UserModel($pdo);
                        break;
                    default:
                        $model = null;
                }
                $controller = new $controller($model);
                $controller->$action();
            } catch (Exception $e) {
            }
        } else {
            http_response_code(404);
            exit();
        }
    }
}
