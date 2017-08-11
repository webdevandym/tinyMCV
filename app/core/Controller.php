<?php

namespace app\core;

use app\Helper\Http\Request;

/**
 * Main Class Controller;.
 */
class Controller
{
    public $model;
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function actionIndex(Request $request)
    {
    }
}
