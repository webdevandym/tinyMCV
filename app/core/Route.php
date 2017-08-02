<?php
namespace app\core;

class Route
{
    protected static $host;

    public static function start()
    {
        $controllerName = 'Main';
        $actionName = 'index';
        static::$host =  'http://'.preg_replace("/\/\w+\.php/", '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']).'/';
        $detectPath = substr_count(static::$host, '/');
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        //get name of controller
        if (!empty($routes[$detectPath-2])) {
            $controllerName = $routes[1];
        }

        //get name of action
        if (!empty($route[$detectPath-1])) {
            $actionName = $route[2];
        }

        //add prefix
        $modelName = 'Model'.ucfirst(strtolower($controllerName));
        $controllerName = 'Controller'.ucfirst(strtolower($controllerName));
        $actionName = 'action'.ucfirst(strtolower($actionName));

        $model_path = 'app/Models/' . $modelName . '.php';
        if (file_exists($model_path)) {
            include $model_path;
        }

        $controller_path = 'app/Controllers/' . $controllerName . '.php';
        if (file_exists($controller_path)) {
            include $controller_path;
        } else {
            Route::ErrorPage404();
        }

        $controller = new $controllerName;
        $action = $actionName;

        if (method_exists($controller, $action)) {
            $controller->$action;
        } else {
            Route::ErrorPage404();
        }
    }

    public function ErrorPage404()
    {
        static::$host;
        header('HTTP/1.1 404 Not Found');
        header("Status 404 Not Found");
        header('Location'. static::$host. "404");
    }
}
