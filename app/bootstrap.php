<?php

namespace app;

//autoloader
$loader = require_once __ROOT__.'/vendor/autoload.php';
$loader->addPsr4('app\\', __ROOT__.'/app/');

/*Load ENV configuraton */
$dotenv = new \Dotenv\Dotenv(__ROOT__);
$dotenv->load();
$dotenv->required('LOAD_ENV')->notEmpty();
if (getenv('LOAD_ENV')) {
    $dotenv->required(['SQL_SERVER', 'SQL_LOGIN', 'SQL_DATABASE', 'DEBUG_LOG'])->notEmpty();
}

/*Load Router */
use app\core\Route;

Route::start();
