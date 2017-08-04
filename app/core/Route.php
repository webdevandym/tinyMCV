<?php

namespace app\core;

class Route
{
    protected static $host;
    private static $controllerPath = 'app/Controllers/';
    private static $controllerNameSpace = '\\app\\Controllers\\';
    private static $modelPath = 'app/Models/';

    public static function start()
    {
        $controllerName = 'Main';
        $actionName = 'index';
        $url = new \app\Helper\Http\UrlHelper();
        static::$host = $url->url();
        $detectPath = substr_count(static::$host, '/');
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        //get name of controller

        if (!empty($routes[$detectPath - 4])) {
            $controllerName = $routes[$detectPath - 4];
        }

        //get name of action
        if (!empty($route[$detectPath - 3])) {
            $actionName = $route[$detectPath - 3];
        }

        //add prefix
        $modelName = 'Model'.ucfirst(strtolower($controllerName));
        $controllerName = 'Controller'.ucfirst(strtolower($controllerName));
        $actionName = 'action'.ucfirst(strtolower($actionName));

        $model_path = static::$modelPath.$modelName.'.php';
        if (file_exists($model_path)) {
            include $model_path;
        }

        $controller_path = static::$controllerPath.$controllerName.'.php';
        if (file_exists($controller_path)) {
            include $controller_path;
        } else {
            self::ErrorPage404();
        }

        $fullControlerNamePath = static::$controllerNameSpace.$controllerName;
        $controller = new $fullControlerNamePath();
        $action = $actionName;

        if (method_exists($controller, $action)) {
            $result = $controller->$action();
        } else {
            self::ErrorPage404();
        }

        if (!empty($result)) {
            return $result;
        }
    }

    public function ErrorPage404()
    {
        header('HTTP/1.1 404 Not Found');
        header('Status 404 Not Found');
        header('Location'.static::$host.'404');
    }
}
