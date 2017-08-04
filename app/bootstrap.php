<?php

namespace core;

define('__ROOT__', __DIR__.'/../');
// require_once 'core/Model.php';
// require_once 'core/View.php';
// require_once 'core/Controller.php';
// require_once 'core/route.php';
require_once 'Helper/Autoloader.php';

use app\core\Controller;
use app\core\Model;
use app\core\Route;
use app\core\View;

Route::start();
