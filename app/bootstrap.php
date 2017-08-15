<?php

namespace app;

$loader = require_once __ROOT__.'/vendor/autoload.php';
$loader->addPsr4('app\\', __ROOT__.'/app/');

$dotenv = new \Dotenv\Dotenv(__ROOT__);
$dotenv->load();

use app\core\Route;

Route::start();
