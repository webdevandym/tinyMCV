<?php

namespace app\core;

//return request from front-end
use app\Helper\Http\Request;

class Route
{
    protected static $host;
    private static $controllerPath = 'app/Controllers/';
    private static $controllerNameSpace = '\\app\\Controllers\\';
    private static $modelPath = 'app/Models/';
    private static $enable404 = true;

    public static function start()
    {
        $controllerName = 'Main';
        $actionName = 'index';

        $url = new \app\Helper\Http\UrlHelper();

        static::$host = $url->url();
        $requet = str_replace('/'.$url->getSubFold(), '', $_SERVER['REQUEST_URI']);
        $detectPath = (substr_count(static::$host, '/') > 3) ? 2 : 1;
        $routes = explode('/', $requet);

        //get name of controller

        if (!empty($routes[$detectPath])) {
            $controllerName = $routes[$detectPath];
        }

        //get name of action
        if (!empty($routes[$detectPath + 1])) {
            $actionName = explode('?', $routes[$detectPath + 1])[0];
        }

        //add prefix
        $modelName = 'Model'.ucfirst(strtolower($controllerName));
        $controllerName = 'Controller'.ucfirst(strtolower($controllerName));
        $actionName = 'action'.ucfirst(strtolower($actionName));

        $fullControlerNamePath = static::$controllerNameSpace.$controllerName;

        if (!class_exists($fullControlerNamePath)) {
            return self::ErrorPage404();
        }

        $controller = new $fullControlerNamePath();
        $action = $actionName;

        if (!method_exists($controller, $action)) {
            return self::ErrorPage404();
        }

        $controller->$action(new Request());
    }

    public function ErrorPage404()
    {
        if (static::$enable404) {
            header('HTTP/1.1 404 Not Found');
            header('Status 404 Not Found');
            header('Location: '.static::$host.'404/');
        }
    }
}
